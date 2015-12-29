<?php
class Browser {
    public $URL;
    public $sock;
    public $timeout;
    public $max_response_size;
    public $buffer = '';
    public $headers = array();
    public $body = '';

    public function __construct($URL) {
        $this->URL = $URL;

        $this->timeout = 5;//in seconds
        $this->max_response_size = 1024 * 1024 * 5;
    }

    public function __destruct() {
        if (is_resource($this->sock)) {
            socket_close($this->sock);
        }
    }

    public function setURL($URL) {
        $this->URL = $URL;
    }

    public function parseURL() {
        if (!empty($this->URL)) {
            $info = parse_url($this->URL);
            if (count($info) == 1 && !empty($info['path'])) { // for some reason loadflood.com is considered path
                $this->host = $info['path'];
                $this->port = 80;
                $this->path = '/';
            } else {
                if (!empty($info['host'])) {
                    $this->host = $info['host'];
                } else {
                    throw new URLInvalidException('Invalid URL');
                }

                if (!empty($info['scheme']) && !in_array($info['scheme'], array('http', 'https'))) {
                    throw new URLUnsupportedProtocolException('Unsupported protocol');
                }

                $this->port = !empty($info['port']) ? $info['port'] : ( (!empty($info['scheme']) && $info['scheme'] == 'https') ? 443 : 80 );

                $this->path = !empty($info['path']) ? $info['path'] : '/';
            }
        } else {
            throw new URLEmptyException('URL is empty');
        }
    }

    public function fetch($method = "GET") {
        $this->buffer = '';

        $this->parseURL();
        $this->connect();

        $this->sendRequest($this->getRequestHeaders($method));

        $this->download();
        $this->extractHeaders();
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getBody() {
        return $this->body;
    }

    public function connect() {
        if (is_resource($this->sock)) return;

        $this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->sock, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $this->timeout, 'usec' => 0));
        if(!@socket_connect($this->sock, $this->host, $this->port)) {
            socket_close($this->sock);
            throw new SocketOpenException('Unable to open socket to: ' . $this->host .' on port ' . $this->port);
        }
    }

    public function sendRequest($request) {
        socket_set_nonblock($this->sock);
        $startTime = microtime(true);
        do {
            $wrote = @socket_write($this->sock, $request);
            if ($wrote === false) {
                socket_close($this->sock);
                throw new SocketWriteException('Cannot write to socket');
            }
            if (microtime(true) - $startTime > $this->timeout) {
                socket_close($this->sock);
                throw new SocketWriteException('Writing to socket timed out');
            }
            $request = substr($request, $wrote);
        } while($request);
        socket_set_block($this->sock);
    }

    public function download() {
        do {
            $data = socket_read($this->sock, 1024);
            $this->buffer .= $data;
            if (strlen($this->buffer) > $this->max_response_size) {
                socket_close($this->sock);
                throw new ResponseTooLargeException('Response data exceeds the limit of ' . $this->max_response_size . ' bytes');
            }
        } while ($data && strlen($data) <= 1024);
    }

    public function extractHeaders() {
        $headers_end = strpos($this->buffer, "\r\n\r\n");
        if ($headers_end) {
            $headers_str = substr($this->buffer, 0, $headers_end);
            $this->body = substr($this->buffer, $headers_end+4);
            preg_match_all('/^(.*)/mi', $headers_str, $headers);
            foreach ($headers[1] as $header) {
                $this->headers[] = trim($header);
            }
        }
    }

    public function getRequestHeaders($method = "GET") {
        $headers = array();
        $headers[] = $method . " " . $this->path . " HTTP/1.1";
        $headers[] = "Host: " . $this->host;
        return implode("\n", $headers) . "\n\n";
    }
}

class URLException extends Exception {}
class URLEmptyException extends Exception {}
class URLInvalidException extends Exception {}
class URLUnsupportedProtocolException extends Exception {}
class SocketOpenException extends Exception {}
class SocketWriteException extends Exception {}
class SocketConTimedOutException extends Exception {}
class ResponseTooLargeException extends Exception {}
