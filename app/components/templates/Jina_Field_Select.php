<label>{{component.name}}</label>
<select :name="component.code" v-model="values[component.code]">
  <option v-for="(label, value) in component.values" :value="value">{{label}}</option>
</select>
