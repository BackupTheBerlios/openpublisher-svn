<ul>
<?php foreach($view['link']['wwyd'] as $job):  ?>
        <li><a href="<?php echo $job['link']; ?>"><?php echo $job['text']; ?></a></li>
<?php endforeach;  ?>  
</ul>
