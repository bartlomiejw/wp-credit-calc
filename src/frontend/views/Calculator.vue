<script lang="ts" setup>
import {ref, defineComponent, onMounted} from 'vue'
import type {CSSProperties} from 'vue'
import {getConfig} from '@/shared/pluginHelpers'

defineComponent({
  name: 'calculator',
})
const adminConfig = getConfig()
const settings = adminConfig.settings
const calculatorName = adminConfig.calcName
const calculatorSettings = settings.credit_calc_settings.find(o => o.id === calculatorName);
console.log(settings.credit_calc_settings)
console.log(calculatorName)
console.log(calculatorSettings)
// <--- REACTIVE ---> //
const calculator = {
  content: {
    header: settings?.header || 'kalkulator kredytowy',
  },
  styles: {
    backgroundColor: settings?.background_color || '',
    headerColor: settings?.header_color || '',
    numColor: settings?.num_color || '',
    bodyColor: settings?.body_color || '',
  },
  settings: {
    name: calculatorSettings.credit_name,
    interestRate: Number(calculatorSettings.interest_rate),
    provision: Number(calculatorSettings.provision),
    otherCosts: Number(calculatorSettings.other_costs),
    rrso: Number(calculatorSettings.rrso),
    saleSwitch: calculatorSettings.sale_switch,
    saleNumber: Number(calculatorSettings.sale_number),
    creditAmountMin: Number(calculatorSettings.credit_amount_min),
    creditAmountMax: Number(calculatorSettings.credit_amount_max),
    creditAmountDefault: Number(calculatorSettings.credit_amount_default),
    creditAmountStep: Number(calculatorSettings.credit_amount_step),
    creditPeriodMin: Number(calculatorSettings.credit_period_min),
    creditPeriodMax: Number(calculatorSettings.credit_period_max),
    creditPeriodDefault: Number(calculatorSettings.credit_period_default),
    creditPeriodStep: Number(calculatorSettings.credit_period_step),
  }
}

// <--- METHODS ---> //

interface Mark {
  style: CSSProperties
  label: string
}

type Marks = Record<number, Mark | string>

const periodValue = ref(calculator.settings.creditPeriodDefault)
const periodMarks = reactive<Marks>({
  12: calculator.settings.creditPeriodMin.toLocaleString() + ' mies.',
  60: calculator.settings.creditPeriodMax.toLocaleString() + ' mies.',
})

const amountValue = ref(calculator.settings.creditAmountDefault)
const amountMarks = reactive<Marks>({
  5000: calculator.settings.creditAmountMin.toLocaleString() + ' zł',
  200000: calculator.settings.creditAmountMax.toLocaleString() + ' zł',
})

const creditCosts = computed(() => {
  const provisionCosts = (calculator.settings.provision / 100) * amountValue.value
  const totalInterest = (((calculator.settings.interestRate * (30 / 365) * (amountValue.value /
      100)) * (periodValue.value + 1))) / 2
  return (provisionCosts + totalInterest + calculator.settings.otherCosts).toFixed(0)
})
const fullCreditAmount = computed(() => {
  return (Number(creditCosts.value) + amountValue.value).toFixed(0)
})

const monthRate = computed(() => {
  return (Number(fullCreditAmount.value) / periodValue.value).toFixed(0)
})
</script>

<template>
  <div class="calculator">
    <el-row :gutter="24">
      <el-col :span="24">
        <h2 class="title">{{ calculator.content.header }}</h2>
        <p class="m-0">{{ calculator.settings.name }}</p>
      </el-col>
    </el-row>
    <el-row class="my-10" :gutter="24">
      <el-col :xs="24" :sm="12">
        <h3 class="header-3__thin">Oprocentowanie</h3>
        <h3 class="header-3__color"><strong>{{ calculator.settings.interestRate.toFixed(4) }} %</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="12">
        <h3 class="header-3__thin">Prowizja banku</h3>
        <h3 class="header-3__color"><strong>{{ calculator.settings.provision.toFixed(4) }} %</strong></h3>
      </el-col>
    </el-row>
    <el-row class="mt-10 mb-16" :gutter="24">
      <el-col :xs="24" :sm="7" class="order-1 md:order-1">
        <h3 class="header-3"><strong>Kwota kredytu</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="5" class="order-2 text-left md:order-2 md:text-right">
        <h3 class="header-3__color"><strong>{{ amountValue.toLocaleString() }} zł</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="7" class="order-4 md:order-3">
        <h3 class="header-3"><strong>Okres spłaty</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="5" class="order-5 text-left md:order-4 md:text-right">
        <h3 class="header-3__color"><strong>{{ periodValue }} mies.</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="12" class="order-3 mb-10 md:mb-0 md:order-5">
        <el-slider v-model="amountValue" :step="calculator.settings.creditAmountStep"
                   :min="calculator.settings.creditAmountMin" :show-tooltip="false"
                   :max="calculator.settings.creditAmountMax" :marks="amountMarks"/>
      </el-col>
      <el-col :xs="24" :sm="12" class="order-6 md:mb-0 md:order-6">
        <el-slider v-model="periodValue" :step="calculator.settings.creditPeriodStep"
                   :min="calculator.settings.creditPeriodMin" :show-tooltip="false"
                   :max="calculator.settings.creditPeriodMax" :marks="periodMarks"/>
      </el-col>
    </el-row>
    <el-row class="footer mt-10" :gutter="24">
      <el-col :xs="24" :sm="4">
        <h3 class="header-3__thin">RRSO*</h3>
        <h3 class="header-3__color"><strong>{{ calculator.settings.rrso }} %</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="10">
        <h3 class="header-3__thin">Całkowita kwota do zapłaty</h3>
        <h3 class="header-3__color"><strong>{{ Number(fullCreditAmount).toLocaleString() }} zł</strong></h3>
      </el-col>
      <el-col :xs="24" :sm="10">
        <h3 class="header-3__thin">Całkowity koszt kredytu</h3>
        <h3 class="header-3__color"><strong>{{ Number(creditCosts).toLocaleString() }} zł</strong></h3>
      </el-col>
      <el-col :span="24">
        <p class="legend">* Rzeczywista roczna stopa oprocentowania w rozumieniu ustawy o kredycie konsumenckim</p>
      </el-col>
    </el-row>
    <el-row :gutter="24">
      <el-col :xs="24" :sm="24">
        <h3 class="header-rate">Rata miesięczna: <strong>{{ Number(monthRate).toLocaleString() }} zł</strong></h3>
      </el-col>
    </el-row>
<!--    Nazwa produktu: {{ calculator.settings.name }} <br/>-->
<!--    Oprocentowanie: {{ calculator.settings.interestRate }} % <br/>-->
<!--    Prowizja banku: {{ calculator.settings.interestRate }} % <br/>-->
<!--    Kwota kredytu (min): {{ calculator.settings.creditAmountMin }} zł <br/>-->
<!--    Kwota kredytu (max): {{ calculator.settings.creditAmountMax }} zł <br/>-->
<!--    Kwota kredytu (default): {{ calculator.settings.creditAmountDefault }} zł <br/>-->
<!--    Kwota kredytu (krok): {{ calculator.settings.creditAmountStep }} zł <br/>-->
<!--    Okres spłaty (min): {{ calculator.settings.creditPeriodMin }} mies. <br/>-->
<!--    Okres spłaty (max): {{ calculator.settings.creditPeriodMax }} mies. <br/>-->
<!--    Okres spłaty (default): {{ calculator.settings.creditPeriodDefault }} mies. <br/>-->
<!--    Okres spłaty (krok): {{ calculator.settings.creditPeriodStep }} mies. <br/>-->
<!--    RRSO: {{ calculator.settings.rrso }} % <br/>-->
<!--    Wysokość innych kosztów: {{ calculator.settings.otherCosts }} zł <br/>-->
<!--    Promocja prowizji: {{ calculator.settings.saleSwitch }} <br/>-->
<!--    Wysokość prowizji: {{ calculator.settings.saleNumber }} zł <br/>-->
<!--    Całkowity koszt kredytu {{ creditCosts }} <br/>-->
<!--    Całkowita kwota do zapłaty: {{ fullCreditAmount }} <br/>-->
<!--    Rata miesięczna {{ calculator.settings.creditPeriodDefault / calculator.settings.creditPeriodDefault }} <br/>-->
  </div>
</template>

<style lang="scss" scoped>
:deep(.el-slider__marks-text) {
  font-size: 16px;
  font-weight: 300;
  color: black;

  &:first-of-type {
    transform: translate(-5%);
  }

  &:last-of-type {
    transform: translate(-100%);
  }
}

:deep(.el-slider__stop) {
  height: 10px;
  width: 10px;
}

:deep(.el-slider__button) {
  border: solid 2px v-bind('calculator.styles.headerColor');
}

:deep(.el-slider__runway) {
  height: 10px;
}

:deep(.el-slider__bar) {
  background-color: v-bind('calculator.styles.headerColor');
  height: 10px;
}

.calculator {
  background-color: v-bind('calculator.styles.backgroundColor');
  padding: 16px 20px;
  font-size: 18px;
  color: v-bind('calculator.styles.bodyColor');

  .title {
    font-size: 40px;
    font-weight: 700;
    margin: 0;
    color: v-bind('calculator.styles.headerColor');
  }

  .header-3 {
    font-size: 18px;
    font-weight: 700;
    margin: 0;

    &__color {
      font-size: 18px;
      color: v-bind('calculator.styles.headerColor');
      margin: 0;
    }

    &__thin {
      font-size: 18px;
      font-weight: 300;
      margin: 0;
    }
  }

  .legend {
    font-weight: 300;
    font-size: 12px;
    margin-bottom: 0;
  }

  .header-rate {
    text-align: center;
    font-size: 22px;
    font-weight: normal;
    margin: 0;
    padding: 30px 20px 20px 20px;

    strong {
      color: v-bind('calculator.styles.headerColor');
    }
  }

  .body-text {
    font-size: 18px;
    margin: 0;

    &__color {
      font-weight: 700;
      color: v-bind('calculator.styles.headerColor');
    }
  }

  .footer {
    background-color: white;
    padding: 20px;
  }
}
</style>
