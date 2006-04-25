<ul>
<?php foreach($tpl['user']['wwyd'] as $job):  ?>
        <li><a href="<?php echo $job['link']; ?>"><?php echo $job['text']; ?></a></li>
<?php endforeach;  ?>  
</ul>
