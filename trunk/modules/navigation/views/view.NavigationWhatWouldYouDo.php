<ul>
<?php foreach($tpl['navigation']['wwyd'] as $job):  ?>
        <li><a href="<?php echo $job['link']; ?>"><?php echo $job['text']; ?></a></li>
<?php endforeach;  ?>  
</ul>
