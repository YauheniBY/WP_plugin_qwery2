<h1> <?php esc_html_e('Welcome to qazProperty plugin Page','qazproperty') ?> </h1>
<div class="content">
    <!-- <?php settings_errors(); ?> -->
<form action="options.php" method="post">
    <?php 
        settings_fields('qazproperty_settings'); 
        do_settings_sections('qazproperty_settings');
        submit_button();
    ?>
</form>
</div>