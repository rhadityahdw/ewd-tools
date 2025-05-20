import { defineStore } from 'pinia';

export const useFormStore = defineStore('form', {
  state: () => ({
    activeStep: 0,
    information: {
      borrower_id: '',
      borrower_group: '',
      purpose: 'both',
      economic_sector: '',
      business_field: '',
      borrower_business: '',
      collectibility: 0,
      restructuring: false,
    },
    aspects: [],
    facilities: {
      kieRows: [{ 
        name: 'KIE 1', 
        limit: 0, 
        outstanding: 0, 
        interestRate: 0, 
        principalArrears: 0, 
        interestArrears: 0, 
        pdo: 0, 
        dueDate: 0,
      }],
      kmkeRows: [{ 
        name: 'KMKE 1', 
        limit: 0, 
        outstanding: 0, 
        interestRate: 0, 
        principalArrears: 0, 
        interestArrears: 0, 
        pdo: 0, 
        dueDate: 0,
      }]
    }
  }),
  
  getters: {
    completedSteps: (state) => {
      let completed = -1;
      
      if (Object.values(state.information).some(val => val)) {
        completed = 0;
      }
      
      if (state.aspects.length > 0) {
        completed = 1;
      }
      
      if (state.facilities.kieRows.length > 0 || state.facilities.kmkeRows.length > 0) {
        completed = 2;
      }
      
      return completed;
    },
    
    canAccessStep: (state) => (step: number) => {
      // Hanya bisa maju satu langkah atau kembali ke langkah sebelumnya
      return step <= state.completedSteps + 1 && step >= 0;
    }
  },
  
  actions: {
    setActiveStep(step: number) {
      if (this.canAccessStep(step)) {
        this.activeStep = step;
      }
    },
    
    saveInformationData(data: any) {
      this.information = data;
      this.setActiveStep(1);
    },
    
    saveAspectData(data: any) {
      this.aspects = data;
      this.setActiveStep(2);
    },
    
    saveFacilityData(data: any) {
      this.facilities = data;
    },
    
    resetForm() {
      this.activeStep = 0;
      this.information = {
        borrower_id: '',
        borrower_group: '',
        purpose: 'both',
        economic_sector: '',
        business_field: '',
        borrower_business: '',
        collectibility: 0,
        restructuring: false,
      };
      this.aspects = [];
      this.facilities = {
        kieRows: [{ 
          name: 'KIE 1', 
          limit: 0, 
          outstanding: 0, 
          interestRate: 0, 
          principalArrears: 0, 
          interestArrears: 0, 
          pdo: 0, 
          dueDate: 0,
        }],
        kmkeRows: [{ 
          name: 'KMKE 1', 
          limit: 0, 
          outstanding: 0, 
          interestRate: 0, 
          principalArrears: 0, 
          interestArrears: 0, 
          pdo: 0, 
          dueDate: 0,
        }]
      };
    }
  }
});