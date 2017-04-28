<?php 
	$discount_by_adult = get_post_meta(get_the_ID() , 'discount_by_adult' , true) ; 
	$discount_by_child = get_post_meta(get_the_ID() , 'discount_by_child' , true) ;		
?>


<div class="activity_discount_info">
	<br>
	<?php if (!empty($discount_by_adult) and is_array($discount_by_adult) ) { ?>
	<h4><?php echo __("Adult discount" ,ST_TEXTDOMAIN); ?></h4>
	<table>
		<tr>
			<th>#</th>
			<th><?php echo __("Title" ,ST_TEXTDOMAIN);?></th>
			<th><?php echo __("Number" ,ST_TEXTDOMAIN);?></th>
			<th><?php echo __("Discount" ,ST_TEXTDOMAIN);?></th>
		</tr>
		<?php
			foreach ($discount_by_adult as $key => $value) {
				echo "<tr>";
				echo "<td>".esc_attr($key+1)."</td>";
				echo "<td>".esc_attr($value['title'])."</td>";
				echo "<td>".esc_attr($value['key'])."</td>";
				echo "<td>".esc_attr($value['value'])."%</td>";
				echo "</tr>";
			}
		?>
	</table>
	<?php } ;?>
	<?php if (!empty($discount_by_child) and is_array($discount_by_child) ) { ?>
	<br>
	<h4><?php echo __("Children discount" ,ST_TEXTDOMAIN); ?></h4>
	<table>
		<tr>
			<th>#</th>
			<th><?php echo __("Title" ,ST_TEXTDOMAIN);?></th>
			<th><?php echo __("Number" ,ST_TEXTDOMAIN);?></th>
			<th><?php echo __("Discount" ,ST_TEXTDOMAIN);?></th>
		</tr>
		<?php
			foreach ($discount_by_child as $key => $value) {
				echo "<tr>";
				echo "<td>".esc_attr($key+1)."</td>";
				echo "<td>".esc_attr($value['title'])."</td>";
				echo "<td>".esc_attr($value['key'])."</td>";
				echo "<td>".esc_attr($value['value'])."%</td>";
				echo "</tr>";
			}
		?>
	</table>
	<br>
	<?php } ;?>
</div> 

