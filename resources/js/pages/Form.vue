<script setup lang="ts">
import { Stepper, StepperDescription, StepperIndicator, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Circle, Check, Dot, Book, Folder, FactoryIcon, PartyPopper } from 'lucide-vue-next';
import InformationForm from './InformationForm.vue';
import { computed } from 'vue';
import { useFormStore } from '../stores/formStore';
import FacilityForm from './FacilityForm.vue';
import AspectForm from './AspectForm.vue';

const formStore = useFormStore();

const steps = [
    { 
        id: 0, 
        title: 'Informasi Debitur', 
        component: 'information',
        icon: Folder,
    },
    { 
        id: 1, 
        title: 'Fasilitas', 
        component: 'facilities',
        icon: FactoryIcon,
    },
    { 
        id: 2, 
        title: 'Aspek Penilaian', 
        component: 'aspects',
        icon: PartyPopper
    },
    
];

const activeComponent = computed(() => {
    return steps[formStore.activeStep].component;
})
</script>

<template>
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-[#2E3192] p-4 text-white shadow-md dark:bg-[#1A1D68] dark:text-gray-200 mb-4">
            <h1 class="pl-2 text-2xl font-bold">Penilaian Debitur</h1>
        </div>

        <!-- Stepper -->
        <div class="container mx-auto px-4 py-6">
            <Stepper 
                :value="formStore.activeStep"
                class="flex w-full items-start gap-2 mb-8"
                >
                <StepperItem 
                    v-for="step in steps"
                    :key="step.id"
                    :value="step.id"
                    :completed="formStore.completedSteps >= step.id"
                    v-slot="{ state }"
                    class="relative flex w-full flex-col items-center justify-center"
                    :step="step.id"
                >
                    <StepperSeparator 
                        v-if="step.id !== steps[steps.length - 1].id"
                        class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                    />
                    
                    <StepperTrigger
                        @click="formStore.setActiveStep(step.id)"
                        as-child
                        >
                        <Button 
                            :variant="state === 'completed' || state === 'active' ? 'default' : 'outline'"
                            size="icon"
                            class="z-10 rounded-full shrink-0"
                            :class="[state === 'active' && 'ring-2 ring-ring ring-offset-2 ring-offset-background']"
                        >
                            <Check v-if="state === 'completed'" class="size-5"/>
                            <component v-if="state === 'active'" :is="step.icon"/>
                            <component v-if="state === 'inactive'" :is="step.icon"/>
                        </Button>
                    </StepperTrigger>

                    <StepperTitle
                        :class="[state === 'active' && 'text-primary']"
                        class="text-sm transition lg:text-base"
                    >{{ step.title }}</StepperTitle>
                </StepperItem>
            </Stepper>

            <!-- Form Container -->
            <div v-if="activeComponent === 'information'">
                        <InformationForm
                            :initial-data="formStore.information"    
                        />
                    </div>
                    <div v-if="activeComponent === 'facilities'">
                        <FacilityForm
                            :initial-data="formStore.facilities"
                        />
                    </div>
                    <div v-if="activeComponent === 'aspects'">
                        <AspectForm
                            :initial-data="formStore.aspects"
                        />
                    </div>
        </div>
    </div>
</template>
