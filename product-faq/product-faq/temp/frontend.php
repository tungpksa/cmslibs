<div class="accordion-tabs-widget">
    <div class="panel-group" id="accordion">
        <?php
        $index = 0;
        foreach ($faq_data['question'] as $k => $faq): ?>
        <div class="panel">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" aria-expanded="<?php if($index == 0){ echo 'true';}?>" href="#<?php echo $k;?>"><?php echo $faq;?></a>
            </h4>

            <div id="<?php echo $k;?>" class="panel-collapse collapse<?php if($index == 0){ echo ' in';}?>">
                <div class="panel-body">
                    <?php echo $faq_data['answer'][$k];?>
                </div>
            </div>
        </div>
        <?php $index++;
        endforeach;?>
    </div>
</div>

