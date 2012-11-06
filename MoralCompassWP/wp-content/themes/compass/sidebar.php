
<aside class="sidebar articles-index left">
<?php 
	global $lstTimePeriods;
	if ($lstTimePeriods != null)
	{
		foreach ($lstTimePeriods as $timePeriod)
		{
		?>
	    <section class=article-group>
	        <h6 class="group-title padded-title"><?php echo $timePeriod->timePeriodName?></h6>
	        <ul class=group-list>
	        <?php foreach ($timePeriod->topics as $topic)
	        {
	        ?>
	            <li><a href="index.php/<?php echo $topic->shortName ?>" title="<?php echo $topic->title ?>" data-excerpt="<?php echo $topic->description?>"><?php echo $topic->title ?></a></li>
	        <?php
	        }
	        ?>
	        </ul>
	    </section>
	<?php
		}
	} 
	?>

</aside>