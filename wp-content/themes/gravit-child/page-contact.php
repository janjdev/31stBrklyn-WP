<?php

/*

Template Name: Contact2

Description: customized contct page

*/

?>









<?php get_header();

  // grab recaptcha library
require_once "recaptchalib.php";
// your secret key
$secret = "6Ld9YCsUAAAAAMfoOyHOoyzGupPRDMQOLqcZnDHk";
 
// empty response
$response = null;
 
// check secret key
$reCaptcha = new ReCaptcha($secret);

// if submitted check response
if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}
 ?>





<div class="container content contact-container contact_page"> 

    <!-- Main Content -->     

      <section id="contact" class="twelve columns ">

          <div class="contact_center">  

            <div class="contactinfo clearfix">

              <h1><?php echo apply_filters( 'the_title', get_the_title() ); ?></h1>

              <div class="address">

                <p><?php echo get_post_meta($post->ID, 'address_street', true); ?></p>

                <p><?php echo get_post_meta($post->ID, 'address_city_st_zip', true); ?></p>

              </div>

              <div class="twelve columns reach">

                <div class="six columns directions" type="button" role="button"><span class="fa fa-map-marker"></span><p> Get Directions</p></div>

                <div id="map"><span id="close" class="fa fa-minus-square"></span><div id="map_brklyn"></div> </div>

               <!--  <div id="phone" class="phone"><a href="tel:+1-314-389-8909"><span class="fa fa-mobile-phone"></span><p>Call Us</p></a></div> -->
                <div class="six columns business"><p><a href="mailto:<?php echo get_post_meta($post->ID, 'email', true); ?>"><span class="fa fa-envelope"></span>31STBRKLYN@GMAIL.COM</a></p></div>
              </div>

            

            </div>

          </div>  

        <div class=" twelve columns gif-vid">

          <div class="ten columns shout-out">

            <div class="circle-text">

              <p>Need rehearsal, production or community space? Looking to collaborate?</p><h4>We're All About That!</h4>

              <div class="show_form" type="button" role="button"><p>HOLLA!</p><span class="fa fa-long-arrow-right"></span></div>

             </div>   

          </div>

          <div id="contact_form" class="twelve columns">         

            <span class="fa fa-times-circle-o"></span>

            <form id="the_form" class="form ten columns" action="http://31stbrklyn.com/wp-content/themes/gravit-child/mail.php" method="POST">
              <fieldset>

                <div class="input input--haruki four columns">

                  <input class="input__field input__field--haruki" type="text" id="fName" name="fName" />

                  <label class="input__label input__label--haruki" for="fName">

                  <span class="input__label-content input__label-content--haruki">First Name</span>

                  </label>

                </div>

                <div class="input input--haruki four columns">

                  <input class="input__field input__field--haruki" type="text" id="lName" name="lName" />

                    <label class="input__label input__label--haruki" for="lName">

                      <span class="input__label-content input__label-content--haruki">Last Name</span>

                    </label>

                </div>

                  <div class="input input--haruki four columns">

                    <input class="input__field input__field--haruki" type="email" id="email" name="email" />

                      <label class="input__label input__label--haruki" for="email">

                        <span class="input__label-content input__label-content--haruki">Email</span>

                    </label>

                  </div>

                  <div class="input input--haruki twelve columns">

                    <textarea class="input__field input__field--haruki" type="text" id="mesg" name="mesg" autocomplete="off" required="required"></textarea>

                      <label class="input__label input__label--haruki mesg" for="mesg">

                        <span class="input__label-content input__label-content--haruki">Share Your Idea or Need.</span>

                    </label>

                  </div>
                  <div class="g-recaptcha" data-theme="dark" data-sitekey="6Ld9YCsUAAAAABtGx32pjy3--hopAufKKqyXXSCl"></div>

                  <input type="hidden" name="submitted" value="1"/>

                  <button class="submission">Submit<span class="fa fa-send-o"></span></button>
                  
                </fieldset>  
              </form>               

          </div>

           <!--  <div class="holder">

            <?php if ( has_post_thumbnail() ) : ?>	

              <img src="<?php the_post_thumbnail_url('FULL'); ?>"><?php endif; ?>

              <video loop="loop" autoplay="autoplay" muted="true" preload="auto" poster="img/gif-vid.gif">

                Your browser does not support the video tag. You should <a href="http://whatbrowser.org/">consider updating</a>.

                <source src="img/collab.mp4" type="video/mp4">

                <source src="img/collab.3gp" type="video/3gp">

              </video>

            </div>         -->

        </div>

      </section>

    </div>  





<?php get_footer(); ?>