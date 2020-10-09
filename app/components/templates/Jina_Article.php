  <h2>{{manager.getValue(component, 'title')}}</h2>
  <h3>{{manager.getValue(component, 'subtitle')}}</h3>
  <div style="margin: 10px 0px;" v-if="manager.getDocuments(component, 'image', 'image').length">
    <img style="max-width: 100%;" v-for="image in manager.getDocuments(component, 'image', 'image')" :src="image.url"/>
  </div>
  <div v-html="manager.getValue(component, 'corps')"></div>
  <?php //echo Jina_Form::vueChildren();?>
