<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Button } from '@/components/ui/button'

import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import {
  DateFormatter,
  type DateValue,
  getLocalTimeZone,
  parseDate,
} from '@internationalized/date'
import { CalendarIcon } from 'lucide-vue-next'
import { ref, watch } from 'vue'

const props = defineProps<{
  modelValue: DateValue;
}>();

const parsedDate = parseDate(props.modelValue.toString())

console.log(parsedDate)

const emit = defineEmits<{
  (e: 'update:modelValue', date: string): void
}>();

const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})

const value = ref<DateValue>(parsedDate)

console.log(typeof value.value)

watch(() => parsedDate, (newVal) => {
  value.value = newVal
})

watch(value, (newValue) => {
  if (newValue) {
    emit('update:modelValue', newValue.toString())
  }
}, { immediate: true })

</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :class="cn(
          'w-[280px] justify-start text-left font-normal',
          !value && 'text-muted-foreground',
        )"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        {{ value ? df.format(value.toDate(getLocalTimeZone())) : "Pick a date" }}
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0">
      <Calendar v-model="value" initial-focus />
    </PopoverContent>
  </Popover>
</template>