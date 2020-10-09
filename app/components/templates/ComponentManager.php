<?php
$className = get_called_class();
?>
<template id="<?php echo $className; ?>-template">
  <<?php echo $className::$tag;?> :id="'Jina-'+manager.root.id+'_'+component.id" :data-id="'Jina-'+manager.root.id+'_'+component.id" :class="'JinaComponent '+component.className+' '+(component.classes ? component.classes : '')" :style="manager.getStyles(component)">
    <?php if (Jina_Context::$context->mode == 'admin') { ?>
    <div class="JinaComponentManager">
      <div class="JinaComponentManagerMenu JinaTop">
        <div>
          <div title="Maintenir pour déplacer" class="JinaComponentHandle btn btn-default btn-xs">
            <i class="fa fa-arrows"></i>
          </div>
          <?php if (self::isBloc()) {?>
            <button title="Gestion des colonnes" class="btn btn-default btn-xs" @click="manager.openPopup($event, component)">
              <i class="fa fa-columns"></i>
            </button>
          <?php }?>
        </div>
        <div class="jina-btn-group">
          <button title="Modifier" class="btn btn-default btn-xs" @click="manager.openPopup($event, component)">
            <i class="fa fa-pencil"></i>
          </button>
          <button title="Copier" class="btn btn-default btn-xs">
            <i class="fa fa-files-o"></i>
          </button>
          <button title="Couper" class="btn btn-default btn-xs">
            <i class="fa fa-cut"></i>
          </button>
          <!--<button title="Sauvegarder dans la librairie" class="btn btn-default btn-xs">
              <i class="fa fa-save"></i>
          </button>-->
          <button title="Supprimer" class="btn btn-danger btn-xs"
                  @click.prevent="manager.deleteComponent(component, $event)">
            <i class="fa fa-trash-o"></i>
          </button>
        </div>
        <div class="jina-btn-group">
          <button title="Ajouter avant" class="btn btn-default btn-xs" @click.prevent="manager.showLibrary(component, 0)">
            <i class="fa fa-plus"></i>
          </button>
          <button title="Coller avant" disabled="disabled" class="btn btn-default btn-xs">
            <i class="fa fa-clipboard"></i>
          </button>
        </div>
      </div>
      <?php
      ?>
      <div class="JinaComponentContent">
        <?php
        }
        include 'app/components/templates/' . get_called_class() . '.php';
        if (Jina_Context::$context->mode == 'admin') {
        ?>
      </div>
      <?php
      ?>
      <div class="JinaComponentManagerMenu JinaBottom">
        <div>
          <div title="Maintenir pour déplacer" class="JinaComponentHandle btn btn-default btn-xs">
            <i class="fa fa-arrows"></i>
          </div>
          <?php if (self::isBloc()) {?>
            <button title="Gestion des colonnes" class="btn btn-default btn-xs" @click="manager.openPopup($event, component)">
              <i class="fa fa-columns"></i>
            </button>
          <?php }?>
        </div>
        <div class="jina-btn-group">
          <button title="Modifier" class="btn btn-default btn-xs" @click="manager.openPopup($event, component)">
            <i class="fa fa-pencil"></i>
          </button>
          <button title="Copier" class="btn btn-default btn-xs">
            <i class="fa fa-files-o"></i>
          </button>
          <button title="Couper" class="btn btn-default btn-xs">
            <i class="fa fa-cut"></i>
          </button>
          <!--<button title="Sauvegarder dans la librairie" class="btn btn-default btn-xs">
              <i class="fa fa-save"></i>
          </button>-->
          <button title="Supprimer" class="btn btn-danger btn-xs"
                  @click.prevent="manager.deleteComponent(component, $event)">
            <i class="fa fa-trash-o"></i>
          </button>
        </div>
        <div class="jina-btn-group">
          <button title="Ajouter après" class="btn btn-default btn-xs" @click.prevent="manager.showLibrary(component, 1)">
            <i class="fa fa-plus"></i>
          </button>
          <button title="Coller après" disabled="disabled" class="btn btn-default btn-xs">
            <i class="fa fa-clipboard"></i>
          </button>
        </div>
      </div>
  </div>
  <?php echo $className::afterTemplate();?>
    </<?php echo $className::$tag;?>>
    <?php
    }
    ?>
</template>
<?php
$class = get_called_class();
echo $class::vueComponent(); ?>
