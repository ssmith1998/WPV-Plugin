<template>
<div class="d-flex align-items-center">
<p class="m-0 pe-1">{{label}}</p>
 <label class="switch">
  <input type="checkbox" v-model="checkVal" @change="onToggle">
  <span class="slider round"></span>
</label>
</div>
</template>

<script>
export default {
name: 'Toggle',
data() {
    return {
        checkVal: true
    }
},
props: {
    label: {
        type: String,
        default: 'Label Here'
    },
    showNew: {
        type: Boolean,
        default: true,
    },
},
methods: {
    onToggle() {
        this
       this.$emit('onChange', this.checkVal);
    },
},
mounted() {
    this.checkVal = this.showNew;
}
}
</script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: rgb(35, 35, 106);
}

input:focus + .slider {
  /* box-shadow: 0 0 1px #2196F3; */
  box-shadow: rgb(35, 35, 106);
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>