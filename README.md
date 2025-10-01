# Tri Lightbox Gallery

Galería 1+2 para WordPress con **lightbox agrupado** y **layout responsive**.  
Pensada para plantillas de **Elementor + JetEngine/ACF**: lee **3 metacampos** (IDs de media) o un **metacampo de galería** (array de IDs) y muestra:

- 1 imagen grande arriba (ratio **1393×879**)  
- 2 imágenes abajo (ratio **687×532**)  
- Al hacer clic, se abre un **único lightbox** con navegación entre las 3.

Las miniaturas se renderizan como **background cover centrado**, garantizando encuadre perfecto.

---

## ✨ Características

- Layout **1 + 2** limpio y reutilizable (shortcode)
- Lightbox único con navegación (**GLightbox**)
- Soporta **3 metacampos** individuales o **1 metacampo de galería**
- **Cantos rectos** (sin border-radius)
- **Responsive** (en móvil, las inferiores pasan a 1 columna)
- Funciona con o sin Elementor (no depende de él)

---

## ✅ Requisitos

- WordPress 5.8+
- PHP 7.4+
- (Opcional) JetEngine o ACF para gestionar metacampos

---

## 📦 Instalación

1. Crea la carpeta del plugin:

wp-content/plugins/tri-lightbox-gallery/


2. Copia el archivo [tri-lightbox-gallery.php](cci:7://file:///Users/pablomalavevelez/Desktop/tri-light-box-gallery/tri-lightbox-gallery.php:0:0-0:0) dentro de esa carpeta.
3. Activa **Tri Lightbox Gallery** en **Plugins → Instalados**.

---

## 🧱 Campos recomendados (JetEngine/ACF)

- Tres campos **Media** que devuelvan **ID**:
  - `imagen_1` 
  - `imagen_2` 
  - `imagen_3` 

**o** un campo **Gallery** (array de IDs), por ejemplo `sofa_gallery`

---

## 🧩 Uso (shortcode)

### Con 3 metacampos

[tri_lightbox meta1="imagen_1" meta2="imagen_2" meta3="imagen_3"]

Parámetros opcionales
size → tamaño de miniatura para la URL (por defecto: large)
full_size → tamaño que se abre en el lightbox (por defecto: full)
post_id → ID de otro post si quieres leer sus metadatos (por defecto: el actual)


[tri_lightbox meta1="imagen_1" meta2="imagen_2" meta3="imagen_3" size="medium" full_size="full"]


🎨 Estructura HTML generada


<div class="tri-gallery" data-tri-gallery="tri-123-4567">
  <div class="tri-gallery__top">
    <a href="imagen-full.jpg" data-gallery="tri-123-4567">
      <span class="tri-bg" style="background-image:url('imagen-thumb.jpg');"></span>
    </a>
  </div>
  <div class="tri-gallery__grid">
    <a href="imagen2-full.jpg" data-gallery="tri-123-4567">
      <span class="tri-bg" style="background-image:url('imagen2-thumb.jpg');"></span>
    </a>
    <a href="imagen3-full.jpg" data-gallery="tri-123-4567">
      <span class="tri-bg" style="background-image:url('imagen3-thumb.jpg');"></span>
    </a>
  </div>
</div>

🔧 Personalización CSS
El plugin incluye CSS básico. Para personalizar:

/* Cambiar gap entre imágenes */
.tri-gallery {
  gap: 24px;
}

/* Personalizar hover effect */
.tri-gallery a:hover .tri-bg {
  transform: scale(1.05);
}

/* Cambiar aspect ratios */
.tri-gallery__top a {
  aspect-ratio: 16/9; /* En lugar de 1393/879 */
}

.tri-gallery__grid a {
  aspect-ratio: 4/3; /* En lugar de 687/532 */
}

📱 Responsive
Desktop: Layout 1 + 2 (imagen grande arriba, 2 pequeñas abajo)
Mobile (≤640px): Las imágenes inferiores pasan a 1 columna
🐛 Solución de problemas
Las imágenes no aparecen:

Verifica que los metacampos contengan IDs de media válidos
Comprueba que las imágenes existan en la biblioteca de medios
El lightbox no funciona:

Asegúrate de que no haya conflictos con otros plugins de lightbox
Verifica que GLightbox se carga correctamente (F12 → Console)
Layout roto en móvil:

El breakpoint responsive es 640px, ajústalo en el CSS si es necesario
📄 Licencia
GPL v2 o posterior

👨‍💻 Autor
Pablo + Chillypills - Versión 1.0.3

