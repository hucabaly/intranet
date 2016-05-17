<?php if(isset($breadcrumb) && count($breadcrumb)): ?>
<ol class="breadcrumb">
    <?php foreach($breadcrumb as $bLink): ?>
        <li>
            <?php if(isset($bLink['url'])): ?>
                <a href="<?php echo $bLink['url']; ?>">
            <?php endif; ?>
                    
            <?php if(isset($bLink['pre_text'])): ?>
                <?php echo $bLink['pre_text']; ?>
            <?php endif; ?>
            
            <span><?php echo $bLink['text']; ?></span>
                    
            <?php if(isset($bLink['after_text'])): ?>
                <?php echo $bLink['after_text']; ?>
            <?php endif; ?>
                    
            <?php if(isset($bLink['url'])): ?>
                    </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ol>
<?php endif;

    
