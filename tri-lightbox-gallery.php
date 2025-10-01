<?php
/**
 * Plugin Name: Tri Lightbox Gallery
 * Description: Galería 1+2 con lightbox agrupado. Lee 3 metacampos (IDs) o un metacampo de galería (array de IDs).
 * Version: 1.0.3
 * Author: Pablo + Chillypills
 */

if (!defined('ABSPATH')) exit;

class Tri_Lightbox_Gallery {
  public function __construct() {
    add_shortcode('tri_lightbox', [$this, 'shortcode']);
    add_action('wp_enqueue_scripts', [$this, 'assets']);
  }

  public function assets() {
    // GLightbox (CDN)
    wp_enqueue_style('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css', [], '3.3.0');
    wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', [], '3.3.0', true);

    // Layout 1 + 2, proporciones fijas, cantos rectos. Miniaturas como background cover centrado.
    $css = "
      .tri-gallery{display:grid;gap:16px}
      .tri-gallery__top{grid-column:1/-1}
      .tri-gallery__grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}

      .tri-gallery a{
        display:block;position:relative;overflow:hidden;border-radius:0;line-height:0;text-decoration:none;
      }

      /* capa visual que pinta la miniatura como background */
      .tri-bg{
        position:absolute;inset:0;display:block;
        background-size:cover !important;
        background-repeat:no-repeat !important;
        background-position:center center !important;
        transition:transform .35s ease;
      }
      .tri-gallery a:hover .tri-bg{transform:scale(1.03)}

      /* Proporciones exactas */
      .tri-gallery__top a{aspect-ratio:1393/879;}
      .tri-gallery__grid a{aspect-ratio:687/532;}

      /* Responsive: en móvil, las de abajo pasan a 1 columna */
      @media (max-width:640px){
        .tri-gallery__grid{grid-template-columns:1fr;}
      }
    ";
    wp_add_inline_style('glightbox', $css);

    // Init GLightbox (un grupo por bloque)
    $js = "document.addEventListener('DOMContentLoaded',function(){
      document.querySelectorAll('[data-tri-gallery]').forEach(function(el){
        GLightbox({ selector: '[data-gallery=\"'+el.dataset.triGallery+'\"]' });
      });
    });";
    wp_add_inline_script('glightbox', $js);
  }

  private function get_img_src($id, $size = 'large') {
    if (!$id) return null;
    $src = wp_get_attachment_image_src(intval($id), $size);
    return $src ? $src[0] : null;
  }

  public function shortcode($atts) {
    $atts = shortcode_atts([
      'meta1' => '',
      'meta2' => '',
      'meta3' => '',
      'gallery_meta' => '',
      'size' => 'large',         // tamaño de miniatura (background)
      'full_size' => 'full',     // tamaño para lightbox
      'post_id' => get_the_ID(),
    ], $atts);

    $post_id = intval($atts['post_id']);
    $ids = [];

    if ($atts['gallery_meta']) {
      $raw = get_post_meta($post_id, $atts['gallery_meta'], true);
      if (is_array($raw)) {
        foreach ($raw as $item) {
          if (is_array($item) && isset($item['id'])) $ids[] = intval($item['id']);
          elseif (is_numeric($item)) $ids[] = intval($item);
        }
      }
    } else {
      foreach (['meta1','meta2','meta3'] as $k) {
        $key = $atts[$k];
        if ($key) {
          $id = get_post_meta($post_id, $key, true);
          if (is_array($id) && isset($id['id'])) $id = $id['id'];
          if ($id) $ids[] = intval($id);
        }
      }
    }

    $ids = array_values(array_filter(array_unique($ids)));
    if (count($ids) < 1) return '';
    $ids = array_slice($ids, 0, 3); // 1 arriba + 2 abajo

    $group = 'tri-'. $post_id .'-'. wp_rand(1000,9999);

    ob_start(); ?>
    <div class="tri-gallery" data-tri-gallery="<?php echo esc_attr($group); ?>">
      <?php
        // Arriba
        $first = array_shift($ids);
        $thumb = $this->get_img_src($first, $atts['size']);
        $full  = $this->get_img_src($first, $atts['full_size']);
        if ($thumb && $full): ?>
          <div class="tri-gallery__top">
            <a href="<?php echo esc_url($full); ?>" data-gallery="<?php echo esc_attr($group); ?>" aria-label="Abrir imagen 1">
              <span class="tri-bg" style="background-image:url('<?php echo esc_url($thumb); ?>');"></span>
            </a>
          </div>
      <?php endif; ?>

      <?php if (!empty($ids)): ?>
        <div class="tri-gallery__grid">
          <?php
            $i = 2;
            foreach ($ids as $id):
              $t = $this->get_img_src($id, $atts['size']);
              $f = $this->get_img_src($id, $atts['full_size']);
              if (!$t || !$f) continue; ?>
              <a href="<?php echo esc_url($f); ?>" data-gallery="<?php echo esc_attr($group); ?>" aria-label="Abrir imagen <?php echo $i++; ?>">
                <span class="tri-bg" style="background-image:url('<?php echo esc_url($t); ?>');"></span>
              </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
  }
}
new Tri_Lightbox_Gallery();
