<form class="ui helpdesk form" action="<?php print Juri::current();?>" method="post">

  <div class="field">
    <textarea name="helpdesk[question]" placeholder="Ask your question here..."></textarea>
  </div>

  <?php if($showName) : ?>
  <div class="field">
    <input type="text" name="helpdesk[name]" placeholder="Full name...">
  </div>
  <?php endif; ?>
  
  <?php if($showNumber) : ?>
  <div class="field">
    <input type="text" name="helpdesk[number]" placeholder="Phone number...">
  </div>
  <?php endif; ?>

  <?php if($showEmail) : ?>
  <div class="field">
    <input type="email" name="helpdesk[email]" placeholder="Email address...">
  </div>
  <?php endif; ?>
  
  <div class="ui error message">
    <div class="header">We noticed some issues</div>
  </div>

  <div class="ui right labeled helpdesk icon submit small button">Submit<i class="right arrow icon"></i></div>

  <input type="hidden" name="helpdesk[birthday]" value="" style="display:none;" />

  <input type="hidden" name="helpdesk[token]" value="<?php print uniqid(); ?>" />

</form>

<script>

  // Ready state 
  jQuery(document).ready(function() {
    
    // Validation
    $('.ui.helpdesk.form').form({
      fields: {
        fullName: {
        identifier  : 'helpdesk[name]',
        rules: [{
            type   : 'empty',
            prompt : 'Please enter your full name'
          }]
        },
        number: {
        identifier  : 'helpdesk[number]',
        rules: [{
            type   : 'empty',
            prompt : 'Please enter your contact number'
          },{
            type : 'length[10]',
            prompt : 'Your contact number is too short'
          },{
            type : 'maxLength[10]',
            prompt : 'Your contact number is too long'
          }]
        },
        email: {
        identifier  : 'helpdesk[email]',
        rules: [{
            type   : 'empty',
            prompt : 'Please enter your email address'
          },{
            type   : 'email',
            prompt : 'Please enter a valid email address'
          }]
        },
        question: {
        identifier  : 'helpdesk[question]',
        rules: [{
            type   : 'empty',
            prompt : 'Please enter your question'
          }]
        }
      }
    });

    // Submit the form on click
    jQuery(".ui.helpdesk.button").click(function(){
      jQuery(".ui.helpdesk.form").submit();
    });

  });

</script>