<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

		<!-- The required Stripe lib -->
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    </head>
    <body>
        <div class="container">

		    <div class="col-md-9">
		        
				@if (Auth::user())

					{!! Form::open(['id' => 'payment-form', 'role' => 'form']) !!}
					<span class="payment-errors"></span>
					
					<div class="form-group">
						{!! Form::label('', 'Card Number:', ['class' => 'field_title']); !!}
						{!! Form::text('', '', ['id' => '', 'data-stripe' => 'number', 'class' => 'default-value form-control active required']); !!}
					</div>
					
					<div class="form-group">
						{!! Form::label('', 'CVC:', ['class' => 'field_title']); !!}
						{!! Form::text('', '', ['id' => '', 'data-stripe' => 'cbc', 'class' => 'default-value form-control active required']); !!}
					</div>
					
					<div class="form-group">
						{!! Form::label('', 'Exp Month', ['class' => 'field_title']); !!}
						{!! Form::selectMonth(null, null, ['id' => '', 'placeholder' => '', 'data-stripe' => 'exp-month', 'class' => 'default-value form-control active required']); !!}
					</div>
					
					<div class="form-group">
						{!! Form::label('', 'Exp Year', ['class' => 'field_title']); !!}
						{!! Form::selectYear(null, date('Y'), date('Y') + 10, null, ['id' => '', 'placeholder' => '', 'data-stripe' => 'exp-year', 'class' => 'default-value form-control active required']); !!}
					</div>
					
					<br/>
					
					<div class="form-group">
						{!! Form::hidden('repo_id', $resource->id) !!}
						{!! Form::hidden('type', strtolower($resourceTitle)) !!}
						{!! Form::submit('Submit', ['class' => 'btn btn-success', 'title' => 'Submit']) !!} 
					</div>
					
					{!! Form::close() !!} <!-- .form --> 
			
				@else
					<p>You must first sign up/log in to purchase.</p>
				@endif

		    </div>

        </div>
    </body>

  <script type="text/javascript">
		  jQuery(document).ready(function(){
		   var StripeBilling = {
		    init: function() {
		       this.form = $('#payment-form');
		       this.submitButton = this.form.find('input[type=submit]');
		       this.submitButtonValue = this.submitButton.val();
		
		       var stripeKey = $('meta[name="publishable-key"]').attr('content');
		       Stripe.setPublishableKey(stripeKey);
		
		       this.bindEvents();
		    },
		
		    bindEvents: function() {
		        this.form.on('submit', $.proxy(this.sendToken, this));
		    },
		
		    sendToken: function(event) {
		        this.submitButton.val('One Moment').prop('disabled', true);
		
		        Stripe.createToken(this.form, $.proxy(this.stripeResponseHandler, this));
		
		        event.preventDefault();
		    },
		
		    stripeResponseHandler: function(status, response) {
		        console.log(status, response);
		        if (response.error) {
		            this.form.find('.payment-errors').show().text(response.error.message);
		            return this.submitButton.prop('disabled', false).val(this.submitButtonValue);
		        }
		
		        $('<input>', {
		            type: 'hidden',
		            name: 'stripe-token',
		            value: response.id
		        }).appendTo(this.form);
		
		        this.form[0].submit();
		    }
		};
		
		StripeBilling.init();
		})();

  </script>
</html>