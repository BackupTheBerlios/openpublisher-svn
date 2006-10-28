<ul>
<?php foreach($tpl['article']['wwyd'] as $job):  ?>
        <li><a href="<?php echo $job['article']; ?>"><?php echo $job['text']; ?></a></li>
<?php endforeach;  ?>  
</ul>
