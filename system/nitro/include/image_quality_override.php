<?php
if (getNitroPersistence('Enabled') && getNitroPersistence('ImageCache.OverrideCompression')) {
  $nitro_quality = getNitroPersistence('ImageCache.JPEGCompression');
  $nitro_quality = !empty($nitro_quality) ? $nitro_quality : $quality;
  $quality = $nitro_quality < 0 ? 0 : ($nitro_quality > 100 ? 100 : $nitro_quality);
}