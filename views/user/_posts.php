<?php
				foreach($posts as $post):
			?>
			<div class="post-item row">
				<div class="span1">
						<img src="<?php  echo $user->gravatar('50');?>" alt="">
					</div>
				<div class="span6">
					
					

						<strong><?php echo $user->name;?> </strong>
						<p>
							<?php echo $post->content; ?>
						</p>
						<p>
						<?php echo $post->date_created; ?>
						</p>	
					
					
				</div>
				<div class="span1">
					<?php if($is_current_user){ ?>
						<a href="<?php echo $this->make_route('/post/delete/' . $post->_id . '/' . $post->_rev) ?>" class="delete">(DELETE)</a>
					<?php }?>	
				</div>
				<div class="span8"></div>
			</div>
		<?php endforeach;?>