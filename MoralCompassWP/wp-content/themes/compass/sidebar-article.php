<aside class="sidebar article-sidebar left">
    <?php global $template; //echo "template is: " . $template; exit(); 
			global $current_user;
      		get_currentuserinfo();
      		global $currentStudentId;
	?>
	
	<?php if ($template == "MORALCOMPASS") : ?>
	<section class=login-area>
		<?php if (!is_user_logged_in()) : ?>
            <p class="controls no-bottom clearfix">
				<a href="<?php echo wp_login_url( $_SERVER['REQUEST_URI'] ); ?>" ><img src="<?php echo ABSURL ?>wp-content/themes/compass/img/log-in.png"></img></a>
				<br/>
				<a class=register-left href="<?php echo ABSURL ?>wp-signup.php">(or Register to get started)</a>
				<!-- <?php echo wp_register('(or ', ' to get started)'); ?>  -->
            </p>
		<?php else: ?>
            <p class="loggedin-status-left no-bottom clearfix">
            	<b>Logged in as <?php echo $current_user->display_name; ?> </b>
            </p>
            <p class="controls no-bottom clearfix">
				<br/>
				<?php wp_loginout($_SERVER['REQUEST_URI']); ?>
				<!-- <a href="<?php echo wp_logout_url(); ?>" >Log out</a> -->
            </p>
         <?php endif; ?>
    </section>
	<section class=article-index>
        <h6 >CONTENTS</h6>
        <ol>
            <li>1) Introduction</li>
            <li>2) Resources</li>
            <li>3) Scorecard</li>
            <li>4) Comments</li>
            <li>5) Bibliography/Links</li>
        </ol>
    </section>
    
    <?php	global $userScoreCardItems;
    		if ($userScoreCardItems != null) :
    ?>
    
    <section class=your-scorecard>
        <h6 class=padded-title>YOUR SCORECARD</h6>
		<table cellpadding=0 cellspacing=0 class="score-table no-bottom">
		<?php 
			global $userScoreCardItems;
			foreach ($userScoreCardItems as $scoreCardItem)
			{
				echo "<tr>";
				echo "<th class=left-header>" . $scoreCardItem->who . "</th>";
				echo "<td class=grade>" . "<div class='" . $scoreCardItem->scoreToClass() . "'>" . $scoreCardItem->scoreToGrade() . "</div></td>";
				echo "</tr>";
				echo "<tr>";
				if ($scoreCardItem->id != -1 && !empty($scoreCardItem->description))
					echo "<td colspan=2 class='left-description'><div class=description-text>" . $scoreCardItem->description . "</div></td>";
				else
					echo "<td colspan=2 class='left-description'><div class=description-text>&nbsp;</div></td>";
				
				echo "</tr>";
			}
		?>
		</table>
    </section>
	<?php if (is_user_logged_in()) : ?>
            <p class="controls no-bottom clearfix">
                <button id=expand-scoreboard2 class="expand left" onclick="javascript:setState(4)">Add/Change Grades</button>
            </p>
	<?php endif; // is_user_logged_in ?>
    <?php endif; // $userScoreCardItems != null?>
    <?php endif; // $template == "MORALCOMPASS"?>
    
    
    <?php if ($template == "WRITING") : ?>
		<?php if (!is_user_logged_in()) : ?>
            <p class="controls no-bottom clearfix">
				<?php wp_loginout($_SERVER['REQUEST_URI']); ?>
				<br/>
				<a class=register-left href="<?php echo ABSURL ?>wp-signup.php">(or Register to get started)</a>
				<!-- <?php echo wp_register('(or ', ' to get started)'); ?>  -->
            </p>
		<?php else: ?>
            <p class="loggedin-status-left no-bottom clearfix">
            	<b>Logged in as <?php echo $current_user->display_name; ?> </b>
            </p>
            <p class="controls no-bottom clearfix">
				<br/>
				<?php wp_loginout($_SERVER['REQUEST_URI']); ?>
				<!-- <a href="<?php echo wp_logout_url(); ?>" >Log out</a> -->
            </p>
         <?php endif; ?>
		<?php if (loggedInAsTeacher()) : ?>
		<br/><br/>
		 <h2><?php _e('students'); ?></h2>
		   <form action="" method="post">
		   <?php wp_dropdown_users(array('name' => 'student_user_id', 'selected' => $currentStudentId, 'include' => '5,6')); ?>
		   <input type="submit" name="submit" value="view" />
		   </form>
		<?php endif; ?>
		<br/>
		
		<h2><a href="educational-product">Return to educational product main page</a></h2>
     <?php endif; ?>
</aside>