<div id="JinaLibrary">
  <ul>
    <library :node="collection" :component="component" :manager="manager" :after="after"></library>
  </ul>
</div>
<template id="jinalibrary-template">
    <li class="Jina_Library_Entry" v-if="node.available" @click.prevent="insert($event, node, manager, component, after)">
      <template v-if="!node.root">{{node.label}}</template>
      <ul v-if="node.type == 'folder' || !node.label">
        <library v-for="(child, className) in node.children" :key="className" :node="child" :component="component" :manager="manager" :after="after">

        </library>
      </ul>
    </li>
</template>
