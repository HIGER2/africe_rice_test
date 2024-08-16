

<script setup>
import { onMounted, onUpdated, ref } from "vue";
import { useManagerStore } from "../js/useManagerStore"
const {type,data,employee}= defineProps(['type','data','employee'])
const useManager = useManagerStore();
const isLoading = ref(false);
const today = new Date();

let minDate = today.toISOString().split('T')[0];

        // Ajouter 30 jours
today.setDate(today.getDate() + 30);

// Obtenir la date au format ISO (YYYY-MM-DD) pour l'attribut max
let maxDate = today.toISOString().split('T')[ 0 ];

let checkDate = () => {
    if (useManager.user.taking_date != "") {
        let newDate = today.toISOString().split('T')[ 0 ]
        if (useManager.user.taking_date < newDate) {
            useManager.user.taking_date = ""
        }
    }


}
// today.setDate(today.getDate() + 30);

// Afficher la nouvelle date
// console.log(today);
// console.log(today.toISOString());


const onSave = async (type) => {

    let confirmS = confirm('are you sure you want to save')
    if (confirmS) {
        isLoading.value = true
        await save(type)
        isLoading.value = false
        scrollTo(0,0)
    }

 }
const {
    Total_Amount,
    separatorMillier,
    save,
    calculate,
    initial,
    destroy
    } = useManager

const loadChild = () => {
    let child = Number(useManager.user.number_child);

    if (!isNaN(child) && isFinite(child)) {
        if (child <= 4) {
            const numberOfChildren = useManager.user.number_child;
            if (useManager.user.children.length > numberOfChildren) {
                useManager.user.children = useManager.user.children.slice(0, numberOfChildren);
            } else {
                for (let index = 0; index < useManager.user.number_child; index++) {
                    useManager.user.children.push({
                        "age": '',
                        "sex": ""
                    })
                }
            }

        } else {
            useManager.user.number_child = ""
            useManager.user.children.length= 0
        }


    } else {
        useManager.user.number_child = ""
        useManager.user.children.length= 0
    }


}

const limit_age = (index) => {

    let age = Number(useManager.user.children[index].age);
    if (!isNaN(age) && isFinite(age)) {
        if (age > 23|| age < 1) {
        useManager.user.children[index].age= ""
        }
    } else {
        useManager.user.children[index].age= ""
    }
}


const count = () => {

};
</script>

<template>
    <div class="col" >
        <!-- {{ useManager.user.marital_status }}
        {{ useManager.test }} -->
        <form @submit.prevent="onSave(type)">
                    <div class="card">
                        <h5 > General information </h5>
                        <div class="form-group">
                            <label for="number">departure date</label>
                            <input type="date"
                                v-model="useManager.user.depart_date"
                                :min="minDate"
                                :disabled="data?.status_input"
                                name="date"
                                placeholder="Enter your departure date" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Date of taking up office (from 30 days)</label>
                            <input type="date"
                                v-model="useManager.user.taking_date"
                                @input="checkDate()"
                                :min="maxDate"
                                :disabled="data?.status_input"
                                name="date"
                                placeholder="Enter your departure date" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="email" :value="employee?.category" id="category" name="category"
                                placeholder="Your category" disabled>
                        </div>

                        <div class="form-group">
                            <label for="password">Spouse</label>
                            <select name="" id="" required v-model="useManager.user.marital_status"
                                :disabled="data?.status_input">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                          <div class="form-group">
                            <label for="number">Number of children (limit 4)</label>
                            <input type="tel" @input="loadChild()" v-model.number="useManager.user.number_child"
                                min="1"
                                max="4"
                                :disabled="data?.status_input" id="password" name="password"
                                placeholder="Number of children (limit 4)" required>
                        </div>
                        <div class="contentchild" v-if="useManager.user.children.length > 0">
                            <!-- {{ useManager.children }} -->
                            <h5>Children informations</h5>
                            <div class="rowinput" v-for="(item, index) in useManager.user.children" :key="index">
                                <div class="form-group">
                                    <label for="age">Age (limit 23 years)</label>
                                    <input type="number"
                                    @input="limit_age(index)"
                                    :disabled="data?.status_input" v-model="item.age" id="age" min="0"
                                    max="23"
                                    name="age" placeholder="Enter age" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Sex</label>
                                    <select name="" id="" required v-model="item.sex" :disabled="data?.status_input">
                                        <option value=""></option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" v-if="!data?.status_input" class="login-button" :disabled="data?.status_input">
                        <span v-if="isLoading">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z">
                                    <animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite"
                                        type="rotate" values="0 12 12;360 12 12" />
                                </path>
                            </svg>
                        </span>
                        <span v-else>
                            Confirm
                        </span>
                    </button>

                <button v-else type="button" class="test" @click="destroy(data?.id)">Supprimer pour réessayer</button>
    </form>
    </div>
</template>


<style lang="scss" scoped>

</style>
