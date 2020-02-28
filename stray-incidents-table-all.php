<?php
$query = new WP_Query( array(
	'orderby' => 'title',
	'status' => 'publish',
	'order'   => 'DESC',
	'orderby'=> 'title',
	'posts_per_page' => -1
));

$posts = $query->posts;
?>
<div class="stray-incidents-wrapper">
	<h4>Όλα τα περιστατικά</h4><hr>
	<table class="stray-incidents-table table-table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Ημερομηνία Εντοπισμού</th>
				<th>Περιοχή</th>
				<th>Ονοματεπώνυμο Ιδιοκτήτη</th>
				<th>Κατάσταση</th>
				<th></th>
			</tr>
		</thead>
		<tbody>

		<?php foreach ($posts as $post): ?>
			
			<?php
				$status = get_incident_status($post);
				$date = get_post_meta( $post->ID, 'date', true );
			?>
			
			<tr>
				<td><?php echo $post->post_title; ?></td>
				<td><?php echo $date ? date("d/m/Y", strtotime($date)) : ""; ?></td>
				<td><?php echo get_post_meta( $post->ID, 'area', true ); ?></td>
				<td><?php echo get_post_meta( $post->ID, 'owner_firstname', true ) . " " . get_post_meta( $post->ID, 'owner_lastname', true ); ?></td>
				<td><?php echo $status; ?></td>
				<td><a target="_blank" href="/form?post_id=<?php echo $post->ID; ?>">Επεξεργασία</a></td>
			</tr>
		
		<?php endforeach; ?>
		</tbody>
	</table>
</div>