<script setup>
import { onMounted, ref } from "vue";
import { useManagerStore } from "../js/useManagerStore"
const { employee, type, data } = defineProps([ 'employee', 'type','data' ])

const useManager = useManagerStore();
const isLoading = ref(false);
const today = new Date();

console.log(today.toISOString());
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
    initial
    } = useManager

const loadChild = () => {
    let child = Number(useManager.user.number_child);

    console.log(child);
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
        }


    } else {
        useManager.user.number_child = ""
    }


}
onMounted(() => {
    // console.log(type);
    if (data) {
        initial(data)
    }

    if (employee) {

        useManager.employeeConnected.value = employee

        console.log(useManager.employeeConnected.value.role);
    }
    // employee
});
</script>

<template>
    <!-- {{data }} -->
    <!-- {{ useManager.employeeConnected.value}} -->
    <!-- {{  employee.role}} -->
    <!-- {{ type[2] }} -->
    <!-- {{ useManager.user.total_p_e_t }} -->
    <div class="content">
        <div class="row" :class="{'colunm':data?.status}">
            <div class="col">
                <form @submit.prevent="onSave(type)">
                    <div class="card">
                        <h5> General Information </h5>
                        <div class="form-group">
                            <label for="category">Your category</label>
                            <input type="email" :value="employee?.category" id="category" name="category"
                                placeholder="Your category" disabled>
                        </div>
                        <div class="form-group">
                            <label for="password">are you a mayor?</label>
                            <select name="" id="" required v-model="useManager.user.marital_status"
                                :disabled="data?.status">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="number">Enter your departure date</label>
                            <input type="date"
                                v-model="useManager.user.depart_date"
                                :min="today.toISOString().slice(0, 10)"
                                :disabled="data?.status"
                                name="date"
                                placeholder="Enter your departure date" required>
                        </div>
                          <div class="form-group">
                            <label for="number">Number of child (limit 4)</label>
                            <input type="tel" @input="loadChild()" v-model.number="useManager.user.number_child" min="0"
                                max="4" :disabled="data?.status" id="password" name="password"
                                placeholder="Enter your password" required>
                        </div>
                        <div class="contentchild" v-if="useManager.user.children.length > 0">
                            <!-- {{ useManager.children }} -->
                            <h5>Children informations</h5>
                            <div class="rowinput" v-for="(item, index) in useManager.user.children" :key="index">
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="number" :disabled="data?.status" v-model="item.age" id="age" min="0"
                                        name="age" placeholder="Enter age" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Sex</label>
                                    <select name="" id="" required v-model="item.sex" :disabled="data?.status">
                                        <option value=""></option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" v-if="!data?.status" class="login-button" :disabled="data?.status">
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
                </form>

            </div>
            <div class="col">
                <div class="card ">
                    <h5> Summary </h5>
                    <ul class="items">
                        <template v-if="data?.status">
                            <li class="item" v-for="(item, index) in type" :key="index">
                                <span>{{ item?.name }}</span>
                                <span>XOF {{ separatorMillier(calculate(item)) }}</span>
                                <!-- {{ item?.staff_category }} -->
                            </li>
                             <li class="item total">
                            <span>Total</span>
                            <span>XOF {{ separatorMillier(Total_Amount(type)) }}</span>
                        </li>
                        </template>
                        <template v-else>
                           <template v-for="(item, index) in type" :key="index">
                            <li class="item"  v-if="item.id == 1 || item.id ==3">
                                <span>{{ item?.name }}</span>
                                <span>XOF {{ separatorMillier(calculate(item)) }}</span>
                                <!-- {{ item?.staff_category }} -->
                            </li>
                           </template>
                        </template>
                        <!-- <li class="item">
                            <span>Personal effect Transportation</span>
                            <span>XOF {{ Total_P_E_T }}</span>
                        </li>
                        <li class="item">
                            <span>Family initial accommodation</span>
                            <span>XOF {{ Total_F_I_A }}</span>
                        </li>
                        <li class="item">
                            <span>Unforseen</span>
                            <span>XOF {{ Total_U }}</span>
                        </li>
                        <li class="item">
                            <span>Paliative for change in allowance</span>
                            <span>XOF {{ Total_P_C_A }}</span>
                        </li> -->

                    </ul>
                </div>
            </div>
        </div>

    </div>
</template>


<style lang="scss" >


</style>
