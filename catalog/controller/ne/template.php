<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ControllerNeTemplate extends Controller {
    private $error = array();
    private $template;

    private function template() {

        $this->load->model('ne/template');

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
            $server = defined('HTTPS_IMAGE') ? HTTPS_IMAGE : HTTPS_SERVER . 'image/';
        } else {
            $server = defined('HTTP_IMAGE') ? HTTP_IMAGE : HTTP_SERVER . 'image/';
        }

        $data_param = array(
            'template_id' => $this->request->post['template_id'],
            'language_id' => (int)(isset($this->request->post['language_id']) ? $this->request->post['language_id'] : $this->config->get('config_language_id'))
        );
        $template = $this->model_ne_template->getTemplate($data_param);
        unset($data_param);

        $currency = isset($this->request->post['currency']) ? $this->request->post['currency'] : '';

        if ($template) {

            if (!$template['uri'] || (!file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/') && !file_exists(DIR_TEMPLATE . 'default/template/ne/templates/' . $template['uri'] . '/'))) {
                $template['uri'] = 'default';
            }

            $clear = (isset($this->request->post['clear']) && $this->request->post['clear']);

            $subject = (((isset($this->request->post['subject']) && $this->request->post['subject']) && !$clear) ? html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8') : $template['subject']);

            $body = (((isset($this->request->post['message']) && $this->request->post['message']) && !$clear) ? html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') : $template['body']);

            $text_message = (((isset($this->request->post['text_message']) && $this->request->post['text_message']) && !$clear) ? html_entity_decode($this->request->post['text_message'], ENT_QUOTES, 'UTF-8') : $template['text_message']);

            $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
            $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');

            if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
                $body = str_replace('{logo}', '<!--//logo//--><a href="' . $this->url->link('common/home') . '" target="_blank"><img src="' . $server . $this->config->get('config_logo') . '" alt=""/></a><!--//end logo//-->', $body);
                $body = $this->replaceTags('<!--//logo//-->', '<!--//end logo//-->', '<a href="' . $this->url->link('common/home') . '" target="_blank"><img src="' . $server . $this->config->get('config_logo') . '" alt=""/></a>', $body);
            } else {
                $body = str_replace('{logo}', '<!--//logo//--><!--//end logo//-->', $body);
                $body = $this->replaceTags('<!--//logo//-->', '<!--//end logo//-->', '', $body);
            }

            if (strpos($body, '{date}') !== false) {
                $localisation_months = $this->config->get('ne_months');
                $localisation_weekdays = $this->config->get('ne_weekdays');
                $body = str_replace('{date}', ($localisation_weekdays[$template['language_id']][(int)date('w')] . ', ' . date('j') . ' ' . $localisation_months[$template['language_id']][(int)date('m') - 1] . ' ' . date('Y')), $body);
            }

            $this->load->model('localisation/language');
            $languages = $this->model_localisation_language->getLanguages();
            $languages_chooser = '';
            if (count($languages) > 1) {
                foreach ($languages as $language) {
                    $languages_chooser .= '<a href="' . $this->url->link('ne/language', 'lang=' . $language['code'] . '&uid={uid}&key={key}') . '" title="' . $language['name'] . '"><img src="' . $server . 'flags/' . $language['image'] . '" alt="' . $language['code'] . '" /></a>&nbsp;';
                }
            }

            $body = str_replace(
                array(
                    '{store_url}',
                    '{show_url}',
                    '{unsubscribe_url}',
                    '{subscribe_url}',
                    '{language}'
                ),
                array(
                    $this->url->link('common/home'),
                    $this->url->link('ne/show', 'uid={uid}'),
                    $this->url->link('ne/unsubscribe', 'uid={uid}&key={key}'),
                    $this->url->link('ne/subscribe', 'uid={uid}&key={key}'),
                    rtrim($languages_chooser, '&nbsp;')
                ),
                $body
            );

            $body = str_replace(
                array(
                    '{defined}',
                    '{special}',
                    '{latest}',
                    '{popular}',
                    '{categories}'
                ),
                array(
                    '<!--//defined//--><!--//end defined//-->',
                    '<!--//special//--><!--//end special//-->',
                    '<!--//latest//--><!--//end latest//-->',
                    '<!--//popular//--><!--//end popular//-->',
                    '<!--//defined_categories//--><!--//end defined_categories//-->'
                ),
                $body
            );

            $data['columns_count'] = $template['product_cols'];

            $data['heading_color'] = $template['heading_color'];
            $data['heading_style'] = $template['heading_style'];

            $data['name_color'] = $template['product_name_color'];
            $data['name_style'] = $template['product_name_style'];

            $data['model_color'] = $template['product_model_color'];
            $data['model_style'] = $template['product_model_style'];

            $data['show_price'] = $template['product_show_prices'];
            $data['show_saving'] = $template['product_show_savings'];

            $data['price_color'] = $template['product_price_color'];
            $data['price_style'] = $template['product_price_style'];

            $data['special_color'] = $template['product_price_color_special'];
            $data['special_style'] = $template['product_price_style_special'];

            $data['old_price_color'] = $template['product_price_color_when_special'];
            $data['old_price_style'] = $template['product_price_style_when_special'];

            $data['description_color'] = $template['product_description_color'];
            $data['description_style'] = $template['product_description_style'];

            $data['saving_color'] = $template['product_savings_color'];
            $data['saving_style'] = $template['product_savings_style'];

            $data['image_width'] = $template['product_image_width'];
            $data['image_height'] = $template['product_image_height'];

            $data['heading'] = '';
            $data['products'] = array();

            $data_setting = array(
                'language_id' => (int)(isset($this->request->post['language_id']) ? $this->request->post['language_id'] : $this->config->get('config_language_id')),
                'customer_group_id' => (int)((isset($this->request->post['customer_group_id']) && $this->request->post['customer_group_id']) ? $this->request->post['customer_group_id'] : $this->config->get('config_customer_group_id')),
                'store_id' => (int)(isset($this->request->post['store_id']) ? $this->request->post['store_id'] : $this->config->get('config_store_id'))
            );

            $data['products'] = array();

            if (((isset($this->request->post['defined']) && $this->request->post['defined']) || (isset($this->request->post['defined_more']) && $this->request->post['defined_more'])) && (!isset($this->request->post['recurrent']) || (isset($this->request->post['recurrent']) && !$this->request->post['recurrent'])))
            {
                $data['heading'] = (isset($this->request->post['defined_text']) && $this->request->post['defined_text']) ? $this->request->post['defined_text'] : $template['defined'];

                if (!isset($this->request->post['defined']) || !$this->request->post['defined']) {
                    $this->request->post['defined'] = array();
                }

                $this->load->model('tool/image');

                foreach ($this->request->post['defined'] as $product_id) {
                    $data_param = array_merge(array('product_id' => (int)$product_id), $data_setting);
                    $result = $this->model_ne_template->getProduct($data_param);

                    if (!$result) {
                        continue;
                    }

                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                }

                $defined = $this->load->view($this->template, $data);

                if (!isset($this->request->post['defined_more']) || !$this->request->post['defined_more']) {
                    $this->request->post['defined_more'] = array();
                }

                foreach ($this->request->post['defined_more'] as $defined_more) {
                    if (!isset($defined_more['products']) || !$defined_more['products']) {
                        continue;
                    }

                    $data['heading'] = isset($defined_more['text']) ? $defined_more['text'] : '';

                    $data['products'] = array();

                    foreach ($defined_more['products'] as $product_id) {
                        $data_param = array_merge(array('product_id' => (int)$product_id), $data_setting);
                        $result = $this->model_ne_template->getProduct($data_param);

                        if (!$result) {
                            continue;
                        }

                        if ($result['image']) {
                            $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                        } else {
                            $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                        }

                        $image = str_replace(' ', '%20', $image);

                        if ($template['product_show_prices']) {
                            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $price = false;
                        }

                        if ($template['product_show_prices'] && (float)$result['special']) {
                            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $special = false;
                        }

                        if ($template['product_show_savings'] && (float)$result['special']) {
                            $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                            if ($saving > 0) {
                                $saving = sprintf($template['savings'], $saving);
                            } else {
                                $saving = false;
                            }
                        } else {
                            $saving = false;
                        }

                        $data['products'][] = array(
                            'product_id'  => $result['product_id'],
                            'thumb'   	  => $image,
                            'name'    	  => $result['name'],
                            'price'    	  => str_replace('$', ':usd', $price),
                            'special'     => str_replace('$', ':usd', $special),
                            'saving'      => $saving,
                            'model'       => (isset($result['model']) ? $result['model'] : ''),
                            'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                            'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                        );
                    }
                    unset($result);

                    $defined .= $this->load->view($this->template, $data);
                }

                $body = $this->replaceTags('<!--//defined//-->', '<!--//end defined//-->', $defined, $body);
            } else {
                $body = $this->replaceTags('<!--//defined//-->', '<!--//end defined//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($this->request->post['defined_categories']) && $this->request->post['defined_categories'])
            {
                $this->load->model('tool/image');

                $defined_categories = '';

                foreach ($this->request->post['defined_categories'] as $category_id) {

                    $data['heading'] = sprintf($template['categories'], $this->model_ne_template->getPath($category_id, $data_setting['language_id']));

                    $data['products'] = array();

                    $sort_data = array(
                        'limit' => empty($this->request->post['defined_category_limit']) ? 0 : $this->request->post['defined_category_limit'],
                        'sort' => empty($this->request->post['defined_category_sort']) ? 'date_added' : $this->request->post['defined_category_sort'],
                        'order' => empty($this->request->post['defined_category_order']) ? 0 : $this->request->post['defined_category_order']
                    );
                    $products = $this->model_ne_template->getProductsByCategoryId($category_id, $sort_data);

                    foreach ($products as $product) {
                        $data_param = array_merge(array('product_id' => (int)$product['product_id']), $data_setting);
                        $result = $this->model_ne_template->getProduct($data_param);

                        if (!$result) {
                            continue;
                        }

                        if ($result['image']) {
                            $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                        } else {
                            $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                        }

                        $image = str_replace(' ', '%20', $image);

                        if ($template['product_show_prices']) {
                            $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $price = false;
                        }

                        if ($template['product_show_prices'] && (float)$result['special']) {
                            $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                        } else {
                            $special = false;
                        }

                        if ($template['product_show_savings'] && (float)$result['special']) {
                            $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                            if ($saving > 0) {
                                $saving = sprintf($template['savings'], $saving);
                            } else {
                                $saving = false;
                            }
                        } else {
                            $saving = false;
                        }

                        $data['products'][] = array(
                            'product_id'  => $result['product_id'],
                            'thumb'   	  => $image,
                            'name'    	  => $result['name'],
                            'price'    	  => str_replace('$', ':usd', $price),
                            'special'     => str_replace('$', ':usd', $special),
                            'saving'      => $saving,
                            'model'       => (isset($result['model']) ? $result['model'] : ''),
                            'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                            'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                        );
                    }
                    unset($result);

                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/categories.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/categories.tpl';
                    } else {
                        $this->template = 'default/template/ne/templates/' . $template['uri'] . '/categories.tpl';
                    }

                    $defined_categories .= $this->load->view($this->template, $data);
                }

                $body = $this->replaceTags('<!--//defined_categories//-->', '<!--//end defined_categories//-->', $defined_categories, $body);
            } else {
                $body = $this->replaceTags('<!--//defined_categories//-->', '<!--//end defined_categories//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($this->request->post['special']) && $this->request->post['special'])
            {
                $data['heading'] = $template['special'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'sort'  => 'p.sort_order',
                    'order' => 'ASC',
                    'start' => 0,
                    'limit' => (int)$template['specials_count']
                ), $data_setting);

                if (!empty($this->request->post['skip_out_of_stock']['special'])) {
                    $data_param['skip_out_of_stock'] = true;
                }

                $product_total = $this->model_ne_template->getTotalProductSpecials($data_param);

                if (!$data_param['limit'])
                {
                    $data_param['limit'] = $product_total;
                }

                $results = $this->model_ne_template->getProductSpecials($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/special.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/special.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/special.tpl';
                }

                $special = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//special//-->', '<!--//end special//-->', $special, $body);
            } else {
                $body = $this->replaceTags('<!--//special//-->', '<!--//end special//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($this->request->post['latest']) && $this->request->post['latest'])
            {
                $data['heading'] = $template['latest'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'sort'  => 'p.date_added',
                    'order' => 'DESC',
                    'start' => 0,
                    'limit' => (int)$template['latest_count']
                ), $data_setting);

                if (!empty($this->request->post['skip_out_of_stock']['latest'])) {
                    $data_param['skip_out_of_stock'] = true;
                }

                $results = $this->model_ne_template->getProducts($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/latest.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/latest.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/latest.tpl';
                }

                $latest = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//latest//-->', '<!--//end latest//-->', $latest, $body);
            } else {
                $body = $this->replaceTags('<!--//latest//-->', '<!--//end latest//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($this->request->post['popular']) && $this->request->post['popular'])
            {
                $data['heading'] = $template['popular'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'limit' => (int)$template['popular_count']
                ), $data_setting);

                if (!empty($this->request->post['skip_out_of_stock']['popular'])) {
                    $data_param['skip_out_of_stock'] = true;
                }

                $results = $this->model_ne_template->getBestSellerProducts($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/popular.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/popular.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/popular.tpl';
                }

                $popular = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//popular//-->', '<!--//end popular//-->', $popular, $body);
            } else {
                $body = $this->replaceTags('<!--//popular//-->', '<!--//end popular//-->', '', $body);
            }

            $data['products'] = array();

            if (isset($this->request->post['recurrent']) && $this->request->post['recurrent'] && isset($this->request->post['random']) && $this->request->post['random'])
            {
                $data['heading'] = $template['defined'];

                $this->load->model('tool/image');

                $data_param = array_merge(array(
                    'sort'  => 'RAND()',
                    'order' => 'DESC',
                    'start' => 0,
                    'limit' => (int)$this->request->post['random_count']
                ), $data_setting);

                $results = $this->model_ne_template->getProducts($data_param);

                foreach ($results as $result) {
                    if ($result['image']) {
                        $image = $this->model_tool_image->resize($result['image'], $template['product_image_width'], $template['product_image_height']);
                    } else {
                        $image = $this->model_tool_image->resize('no_image.jpg', $template['product_image_width'], $template['product_image_height']);
                    }

                    $image = str_replace(' ', '%20', $image);

                    if ($template['product_show_prices']) {
                        $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $price = false;
                    }

                    if ($template['product_show_prices'] && (float)$result['special']) {
                        $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $currency);
                    } else {
                        $special = false;
                    }

                    if ($template['product_show_savings'] && (float)$result['special']) {
                        $saving = round((($result['price'] - $result['special'])/$result['price'])*100, 0);
                        if ($saving > 0) {
                            $saving = sprintf($template['savings'], $saving);
                        } else {
                            $saving = false;
                        }
                    } else {
                        $saving = false;
                    }

                    $data['products'][] = array(
                        'product_id'  => $result['product_id'],
                        'thumb'   	  => $image,
                        'name'    	  => $result['name'],
                        'price'    	  => str_replace('$', ':usd', $price),
                        'special'     => str_replace('$', ':usd', $special),
                        'saving'      => $saving,
                        'model'       => (isset($result['model']) ? $result['model'] : ''),
                        'description' => ((int)$template['product_description_length'] ? mb_substr(trim(preg_replace("/\s\s+/u", ' ', strip_tags(html_entity_decode(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8')))), 0, (int)$template['product_description_length'], 'UTF-8') . '..' : ''),
                        'href'    	  => $this->url->link('product/product', 'product_id=' . $result['product_id']),
                    );
                }
                unset($result);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                } else {
                    $this->template = 'default/template/ne/templates/' . $template['uri'] . '/defined.tpl';
                }

                $latest = $this->load->view($this->template, $data);
                $body = $this->replaceTags('<!--//defined//-->', '<!--//end defined//-->', $latest, $body);
            }

            $body = str_replace(':usd', '$', $body);
        } else {
            $body = '';
            $subject = '';
        }

        return array('subject' => $subject, 'body' => $body, 'text_message' => $text_message);
    }

    public function json() {
        if(empty($this->request->server['HTTP_X_REQUESTED_WITH']) || strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            $this->response->redirect($this->url->link('common/home'));
        }

        $json = (object)$this->template();
        $this->response->addHeader('Content-type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function json_raw() {
        if(empty($this->request->server['HTTP_X_REQUESTED_WITH']) || strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            $this->response->redirect($this->url->link('common/home'));
        }

        $this->load->model('ne/template');

        $data_param = array(
            'template_id' => $this->request->post['template_id'],
            'language_id' => (int)(isset($this->request->post['language_id']) ? $this->request->post['language_id'] : $this->config->get('config_language_id'))
        );
        $template = $this->model_ne_template->getTemplate($data_param);
        unset($data_param);

        if ($template) {
            $body = $template['body'];
            $subject = $template['subject'];
            $text_message = $template['text_message'];

            $subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
            $body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
            $text_message = html_entity_decode($text_message, ENT_QUOTES, 'UTF-8');
        } else {
            $body = '';
            $subject = '';
            $text_message = '';
        }

        $json = (object)array('subject' => $subject, 'body' => $body, 'text_message' => $text_message);
        $this->response->addHeader('Content-type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function replaceTags($start, $end, $text, $source) {
        $result = preg_replace('#('.preg_quote($start).')(.*)('.preg_quote($end).')#si', '$1'.$text.'$3', $source);

        if (preg_last_error() !== PREG_NO_ERROR) {
            $data = explode($start, $source);
            $out = array();
            foreach ($data as $entry) {
                $inner = explode($end, $entry);
                if (count($inner) > 1) {
                    $inner[0] = $text;
                    $out[] = implode($end, $inner);
                } else {
                    $out[] = $entry;
                }
            }
            $result = implode($start, $out);
        }

        return $result;
    }

}