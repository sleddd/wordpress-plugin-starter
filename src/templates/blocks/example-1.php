<?php 
/* 
 * Example 1 - block template 
 */ 
?>
<div class="question-container">
	<pre style="display:none;">
		<?php 
			// Encoding block attributes for frontend js.
			echo wp_json_encode( $attributes );
		?>
	</pre>
</div>
