<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ogainmobiliaria
 */

get_header(); ?>
<!-- SEARCH -->
<section class="first">
  <div class="wrapper">
    <div class="row">
      <div class="search-box">
        <div class="search-field area col col--9-of-12 col--m-2-of-3">
          <input type="text" name="ubicación" placeholder="ubicación">
        </div>
        <div class="search-btn btn col col--3-of-12 col--m-1-of-3">
          <input type="submit" name="buscar" value="buscar" placeholder="buscar">
        </div>
        <div class="search-field price col col--3-of-12 col--m-1-of-2">
          <input type="text" name="precio-desde" placeholder="precio desde">
        </div>
        <div class="search-field price col col--3-of-12 col--m-1-of-2">
          <input type="text" name="precio-hasta" placeholder="precio hasta">
        </div>
        <div class="search-field bat col col--3-of-12 col--m-1-of-2">
          <input type="text" name="aseos" placeholder="aseos">
        </div>
        <div class="search-field bed col col--3-of-12 col--m-1-of-2">
          <input type="text" name="dormitorios" placeholder="dormitorios">
        </div>
      </div>

      <video autoplay loop  poster="<?php bloginfo('template_directory'); ?>/assets//img/first-frame.jpg">
        <source src="<?php bloginfo('template_directory'); ?>/assets/video/oga-inmobiliaria.mp4" type="video/mp4">
        <source src="<?php bloginfo('template_directory'); ?>/assets/video/oga-inmobiliaria.webm" type="video/webm">
      </video>
    </div>
  </div>
</section>
<!-- FIN SEARCH -->


<section class="hero extra-pad">
  <div class="row">
    <div class="discover col col--8-of-12">
      <h2>Descubre la <span>casa de tus sueños</span></h2>
      <p>Queremos que la experiencia de buscar tu hogar sea única y por eso nos comprometemos a ayudarte a encontrar el mejor lugar que se adapte a tus necesidades. Nuestro deseo es que puedas a través de nosotros tener la casa de tus sueños.</p>
    </div>

    <div class="discovery col col--4-of-12">
      <figure class="discovery-card">
        <img src="<?php bloginfo('template_directory'); ?>/assets/img/carabanchel.jpg" alt="Carabanchel">
        <figcaption>Carabanchel</figcaption>
      </figure>
    </div>
  </div>

  <div class="row">
    <div class="discovery col col--4-of-12">
      <figure class="discovery-card">
        <img src="<?php bloginfo('template_directory'); ?>/assets/img/mostoles.jpg" alt="Móstoles">
        <figcaption>Móstoles</figcaption>
      </figure>
    </div>

    <div class="discovery col col--4-of-12">
      <figure class="discovery-card">
        <img src="<?php bloginfo('template_directory'); ?>/assets/img/fuenlabrada.jpg" alt="Fuenlabrada">
        <figcaption>Fuenlabrada</figcaption>
      </figure>
    </div>

    <div class="discovery col col--4-of-12">
        <div class="discover-dream">
          <h2>Un sueño <span>hecho realidad</span></h2>
          <p>Desde el principio hata el fin vamos a estar contigo. Desde el momento en el que estás buscando hasta que encuentres el hogar que siempre soñaste estaremos ahí para lo que necesites.</p>
          <a href="#">Busca tu casa</a>
        </div>
    </div>
  </div>

  <div class="row">
    <div class="col col--4-of-12">
      <div class="discover-us">
        <h2>Conoce más <span>de nosotros</span></h2>
        <p>Somos una inmobiliaria diferente. Ponemos nuestros principios y valores por delante, queremos ser transparentes en todo lo que hacemos.</p>
        <a href="#">Pide una cita</a>
      </div>
    </div>

    <div class="col col--8-of-12">
      <figure class="discovery-card">
        <img src="<?php bloginfo('template_directory'); ?>/assets/img/aravaca.jpg" alt="Aravaca">
        <figcaption>Aravaca</figcaption>
      </figure>
    </div>
  </div>
</section>

<?php
//get_sidebar();
get_footer();
