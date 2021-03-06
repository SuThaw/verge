<div class="page-header" >
	<h1>
		<?php echo $user->full_name;?>
		<?php if($is_current_user){ ?>
			<code>This is you!</code>
		<?php } ?>
	</h1>
</div>
<div class="container">
	<div class="row">
		<div class="span4">
			<div class="well sidebar-nave">
				<ul class="nav nav-list">

					<li><h3>User Information</h3></li>
					<li><img src="<?php  echo $user->gravatar('100');?>" alt=""></li>
					<li><b>Username:</b><?php echo $user->name;?></li>
					<li><b>Email:</b><?php echo $user->email;?></li>
				</ul>
			</div>

		</div>
		<div class="span8">
			<?php if($is_current_user){ ?>
				<h2>Create a new post</h2>
			
			
			<form action="<?php echo $this->make_route('/post')?>" method="post">
				<textarea name="content" id="content" class="span8" rows="3"></textarea>
				<button id="create_post" class="btn btn-primary">Submit</button>
			</form>
			<?php }?>
			<h2 >Posts (<span class="post_count"><?php echo $post_count; ?></span>)</h2>
			<div class="post_list">
				<?php include('_posts.php'); ?>
			</div>
			<div class="load_more" class="row">
				<div class="span8">
					<a href="#" class="more_posts">Load More...</a>
				</div>
			</div>
		</div>
	</div>
	<?php //var_dump($user);?>
</div>