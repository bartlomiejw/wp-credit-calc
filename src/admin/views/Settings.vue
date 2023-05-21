<script lang="ts" setup>
import axios from "axios";
import {getCurrentInstance, reactive, computed, ref, nextTick, toRaw, onBeforeMount, onMounted, inject} from 'vue';

interface OptionsArguments {
  id: number
  name: string
  description: string
  type: string
  options: []
  repeater: {}
}

interface CalculatorSettings {
  id: number
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

type Options = OptionsArguments[]

const win = inject('win')
const oldSettings = {};
const endpoints = {settings: ''};
const ui = reactive({actionKey: 0, loaded: false});
const settings = reactive({...oldSettings});
const repeaterOptions = reactive(<CalculatorSettings>{});
const repeaterSettings = reactive(<CalculatorSettings>{});
const structure = reactive({sections: {}, options: {}});
const hasLoaded = ref(false);
let tabIndex = 0

const hasChanged = computed(() => {
  // compare two objects
  const a = JSON.stringify(settings);
  const b = JSON.stringify(oldSettings);
  ui.actionKey = ui.actionKey + 1;
  return (a === b);
});
const getOptions = (section): Options => {
  const options = {...structure.options};
  const result: OptionsArguments[] = [];

  Object.keys(options).forEach((key) => {
    const item = options[key];
    if (item.section === section) {
      item.id = key;
      result.push(item);
    }
  });

  return result;
};
const getRepeaterOptions = (section): Options => {
  // @ts-ignore
  const options = {...structure.options.credit_calc_settings.repeater};
  const result: Partial<CalculatorSettings> = {};
  Object.keys(options).forEach((key) => {
    result[key] = {
      key: Date.now(),
      value: '',
    }
  });
  // @ts-ignore
  return result;
}

const doSave = async () => {
  try {
    let data = toRaw(settings);
    const rst = await axios.post(endpoints.settings, data);

    if (rst.status === 200) {
      ElMessage({
        message: 'Twoje ustawienia zostały zapisane.',
        type: 'success',
      });

      // @ts-ignore
      const config = win.vue_wp_plugin_config_admin;
      Object.assign(oldSettings, config.settings || {})
      Object.assign(settings, settings || {})

      Object.keys(settings).forEach((key) => {
        oldSettings[key] = settings[key];
        settings[key] = settings[key];
      });

      // force rerendered
      ui.actionKey = ui.actionKey + 1;
    } else {
      ElMessage.error('Odpowiedź wordpress z błędem. ' + JSON.stringify(rst, null, 2));
    }
  } catch (err: any) {
    ElMessage.error('Odpowiedź serwera z błędem. ' + err.message);
  }
};
const doCancel = () => {
  Object.assign(settings, oldSettings || {})

  // copy settings from server output
  Object.keys(settings).forEach((key) => {
    oldSettings[key] = settings[key];
    settings[key] = settings[key];
  });

  // force rerendered
  ui.actionKey = ui.actionKey + 1;
};
const doLoad = async (instance) => {
  await nextTick();
  // @ts-ignore
  const config = win.vue_wp_plugin_config_admin;
  // @ts-ignore
  if (!win.$appConfig.nonce) {
    // @ts-ignore
    win.$appConfig.nonce = config.rest.nonce;
  }

  Object.assign(structure, config.settingStructure || {})
  structure['sections'] = structure['sections'];
  structure['options'] = structure['options'];
  Object.assign(settings, config.settings || {})
  Object.assign(repeaterOptions, getRepeaterOptions('settings') || {})
  Object.assign(repeaterSettings, config.settings.credit_calc_settings || {})
  Object.assign(endpoints, config.rest.endpoints)

  // copy settings from server output
  Object.keys(settings).forEach((key) => {
    oldSettings[key] = settings[key];
    settings[key] = settings[key];
  });

  // make sure data is loaded before ui render
  hasLoaded.value = true;
  instance?.proxy?.$forceUpdate()
}

const addCalculator = (repeaterName: number) => {
  settings[repeaterName].push({
    id: 'kalkulator' + settings[repeaterName].length,
    credit_amount_default: 0,
    credit_amount_max: 0,
    credit_amount_min: 0,
    credit_amount_step: 0,
    credit_period_default: 0,
    credit_period_max: 0,
    credit_period_min: 0,
    credit_period_step: 0,
    interest_rate: 0,
    other_costs: 0,
    sale_number: 0,
    provision: 0,
    rrso: 0,
    sale_switch: false,
  })
}

onBeforeMount(() => {
  const instance = getCurrentInstance();
  // @ts-ignore
  if (win && win.vue_wp_plugin_config_admin) {
    doLoad(instance);
    return;
  }

  document.onreadystatechange = async () => {
    if (document.readyState === 'complete') {
      doLoad(instance);
    }
  };
})
</script>

<template>
  <div class="app-settings w-full flex flex-wrap mx-auto" v-if="hasLoaded">
    <aside class="w-full md:w-1/5 pt-4">
      <div
          class="w-full sticky inset-0 lg:h-auto overflow-x-hidden overflow-y-auto lg:overflow-y-hidden lg:block mt-0 my-2 lg:my-0 border border-gray-400 lg:border-transparent bg-white shadow lg:shadow-none lg:bg-transparent z-20">
        <el-menu
            default-active="1"
            class="rounded shadow bg-white"
        >
          <template v-for="(value, name) in structure.sections" :key="`el-menu-item-${name}`">
            <el-menu-item :index="name">
              <a :href="`/#${name}`">
                <span class="font-bold">{{ value }}</span>
              </a>
            </el-menu-item>
          </template>
        </el-menu>
        <div class="space-x-3 flex justify-end pt-2">
          <el-button
              @click="doSave()"
              style="width: 100px"
              type="primary"
              size="large"
              :data-rerendered="ui.actionKey"
          >
            Zapisz
          </el-button>
        </div>
        <div class="pt-2">
          <p><strong>Widget - strona</strong></p>
          <code class="m-auto block w-100">[creditcalc view='calculator' name='kalkulatora{numer}']</code>
        </div>
      </div>
    </aside>

    <!--Section container-->
    <section class="w-full md:w-4/5 min-h-screen">
      <div class="ml-3">
        <div :id="name" class="pt-4" v-for="(value, name) in structure.sections" :key="`card-${name}`">
          <!--Card-->
          <div class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
            <!--Title-->
            <h2 class="font-sans font-bold break-normal text-gray-700 pb-4 text-xl w-full">
              {{ value }}
            </h2>
            <div class="md:flex mb-4 items-center" v-for="item in getOptions(name)" :key="`input-item-${item.id}`">
              <div class="md:w-3/5">
                <label class="block text-gray-600 font-bold md:text-left mb-3 md:mb-0 pr-4" for="my-checkbox">
                  {{ item.name }}
                </label>
                <p class="text-sm text-gray-600">{{ item.description }}</p>
              </div>
              <div class="md:w-2/5">
                <div v-if="item.type === 'toggle'">
                  <el-switch
                      v-model="settings[item.id]"
                      size="large"
                  />
                </div>
                <div v-else-if="['textarea'].indexOf(item.type) > -1">
                  <el-input
                      v-model="settings[item.id]"
                      :rows="5"
                      size="large"
                      type="textarea"
                      placeholder="Please input"
                  />
                </div>
                <div v-else-if="item.type === 'color'">
                  <el-color-picker v-model="settings[item.id]"/>
                </div>
                <div v-else-if="item.type === 'number'">
                  <el-input-number v-model="settings[item.id]" size="large" :precision="2"
                                   placeholder="Wprowadź liczbę"/>
                </div>
                <div v-else-if="item.type === 'repeater' && name === 'settings'">
                  <el-button size="large" type="primary" @click="addCalculator(item.id)">
                    Utwórz kalkulator
                  </el-button>
                  <input v-model="settings[item.id]" type="hidden"/>
                </div>
                <div v-else>
                  <el-input v-model="settings[item.id]" size="large" placeholder="Wprowadź tekst"/>
                </div>
              </div>
            </div>
            <el-collapse v-if="name === 'settings'">
              <template v-for="item in getOptions(name)" :key="`repeater-${item.id}`">
                <CalcData
                    v-for="(calc, index) in settings[item.id]"
                    :key="`calculator-${index}`"
                    :keyName="index"
                    :removeId="calc['id']"
                    :title="`Kalkulator: ${calc['credit_name']} | Nazwa: ${calc['id']}`"
                    :name="`calculator-${index}`"
                    :old-settings="oldSettings"
                    :settings="settings"
                    :structure="structure"
                />
              </template>
            </el-collapse>
          </div>
          <!--/Card-->
        </div>
      </div>
    </section>
  </div>
</template>

<style lang="scss" scoped>
.active {
  --tw-border-opacity: 1;
  border-color: rgb(202 138 4 / var(--tw-border-opacity));
  font-weight: 700;
}

.overflow-footer {
  height: 100px;
  overflow-y: wrap;
}

.el-menu-item {
  padding: 0 !important;

  a {
    text-decoration: none;
    width: 100%;
    padding: 0 var(--el-menu-base-level-padding);
  }
}
</style>
