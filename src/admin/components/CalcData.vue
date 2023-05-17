<script lang="ts" setup>
interface Props {
  keyName: number
  title: string
  removeId: number
  name: string
  oldSettings: {}
  settings: {}
  structure: { sections: {}, options: {} }
  // Delete: Function
}

interface CalcSettings {
  credit_amount_default: number
  credit_amount_max: number
  credit_amount_min: number
  credit_amount_step: number
  credit_period_default: number
  credit_period_max: number
  credit_period_min: number
  credit_period_step: number
  interest_rate: number
  other_costs: number
  sale_number: number
  provision: number
  rrso: number
  sale_switch: boolean
}

interface OptionsArguments {
  id: number
  name: string
  description: string
  type: string
  repeater: {}
}

type Options = OptionsArguments[]

const props = defineProps<Props>()

// const settings = reactive({...props.oldSettings});
const getRepeaterOptions = (section): Options => {
  // @ts-ignore
  const options = {...props.structure.options.credit_calc_settings.repeater};
  const result: OptionsArguments[] = [];
  Object.keys(options).forEach((key) => {
    const item = options[key];
    if (item.section === section) {
      item.id = key;
      result.push(item);
    }
  });
  return result;
}

const removeCalc = (id: number) => {
  props.settings['credit_calc_settings'] = props.settings['credit_calc_settings'].filter((e) => e.id !== id)
}
</script>
<template>
  <el-collapse-item :title="title" :name="props.name">
    <el-button class="mb-4" size="large" type="danger" @click="removeCalc(props.removeId)">
      Usuń kalkulator
    </el-button>
    <div class="md:flex mb-4 items-center"
         v-for="(item, index) in getRepeaterOptions('settings')"
         :key="`calculator-name-${index}`">
      <input
          v-if="item.type === 'id'"
          :value="props.keyName"
          type="hidden"
      />
      <div class="md:w-3/5" v-if="item.type !== 'id'">
        <label class="block text-gray-600 font-bold md:text-left mb-3 md:mb-0 pr-4" for="my-checkbox">
          {{ item.name }}
        </label>
        <p class="text-sm text-gray-600">{{ item.description }}</p>
      </div>
      <div class="md:w-2/5">
        <div v-if="item.type === 'toggle'">
          <el-switch
              v-model="props.settings['credit_calc_settings'][keyName][item.id]"
              size="large"
          />
        </div>
        <div v-else-if="['textarea'].indexOf(item.type) > -1">
          <el-input
              v-model="props.settings['credit_calc_settings'][keyName][item.id]"
              :rows="5"
              size="large"
              type="textarea"
              placeholder="Please input"
          />
        </div>
        <div v-else-if="item.type === 'color'">
          <el-color-picker v-model="props.settings['credit_calc_settings'][keyName][item.id]"/>
        </div>
        <div v-else-if="item.type === 'number'">
          <el-input-number
              v-model="props.settings['credit_calc_settings'][keyName][item.id]"
              :precision="2"
              size="large"
              placeholder="0"
          />
        </div>
        <div v-else-if="item.type === 'text'">
          <el-input
              v-model="props.settings['credit_calc_settings'][keyName][item.id]"
              size="large"
              placeholder="Wpisz"/>
        </div>
      </div>
    </div>
  </el-collapse-item>
</template>
<style lang="scss" scoped>
::v-deep(.el-collapse-item__header) {
  @apply text-gray-600 font-bold md:text-left mb-3 pr-4 text-lg
}
</style>
