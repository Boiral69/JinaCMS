<template id="Jina_Root-template">
  <?php if (Jina_Context::$context->mode == 'admin') { ?>
  <div class="JinaRootManager"><!-- @mouseover="manager.show(component, $event)" @mouseout="manager.hide(component, $event)">-->
    <div class="JinaRootManagerMenu JinaTop">
      <div class="jina-btn-group">
      </div>
      <div class="jina-btn-group">
        <button title="Undo" class="btn btn-default btn-xs" :class="manager.currentIndex > 0 ? '' : 'disabled'" @click.prevent="manager.backward()">
          <i class="fa fa-undo"></i>
        </button>
        <button title="Redo" class="btn btn-default btn-xs" :class="manager.currentIndex < manager.history.length-1 ? '' : 'disabled'" @click.prevent="manager.forward()">
          <i class="fa fa-repeat"></i>
        </button>
        <button title="Sauver" class="btn btn-default btn-xs" :class="manager.isTouched() ? '' : 'disabled'" @click.prevent="manager.save()">
          <i class="fa fa-save"></i>
        </button>
      </div>
      <div class="jina-btn-group">
        <button title="Paramètres" class="btn btn-default btn-xs">
          <i class="fa fa-cog"></i>
        </button>
      </div>
    </div>
    <?php }?>
    <div class="JinaComponentContent">
      <?php echo Jina_Root::vueChildren(); ?>
    </div>
    <?php if (Jina_Context::$context->mode == 'admin') { ?>
    <div class="JinaRootManagerMenu JinaBottom">
      <div class="jina-btn-group"></div>
      <div class="jina-btn-group">
        <button title="Undo" class="btn btn-default btn-xs" :class="manager.currentIndex > 0 ? '' : 'disabled'" @click.prevent="manager.backward()">
          <i class="fa fa-undo"></i>
        </button>
        <button title="Redo" class="btn btn-default btn-xs" :class="manager.currentIndex < manager.history.length-1 ? '' : 'disabled'" @click.prevent="manager.forward()">
          <i class="fa fa-repeat"></i>
        </button>
        <button title="Sauver" class="btn btn-default btn-xs" :class="manager.isTouched() ? '' : 'disabled'" @click.prevent="manager.save()">
          <i class="fa fa-save"></i>
        </button>
      </div>
      <div class="jina-btn-group">
        <button title="Paramètres" class="btn btn-default btn-xs">
          <i class="fa fa-cog"></i>
        </button>
      </div>
    </div>
  </div>
  <?php }?>
</template>
<?php echo Jina_Root::vueComponent();?>