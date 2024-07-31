<script setup>
import { onMounted, onUpdated, ref } from "vue";
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
    initial,
    destroy
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
            useManager.user.children.length= 0
        }


    } else {
        useManager.user.number_child = ""
        useManager.user.children.length= 0
    }


}

onUpdated(() => {

    // alert('u')
})

if (data) {
        initial(data)
    }

    if (employee) {
        useManager.employeeConnected.value = employee
    }
onMounted(() => {
    // console.log(type);
    // alert('r')
    // if (data) {
    //     initial(data)
    // }

    // if (employee) {
    //     useManager.employeeConnected.value = employee
    // }
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
        <div class="row">
            <div class="col">
                <form @submit.prevent="onSave(type)">
                    <div class="card">
                        <h5> General information </h5>
                        <div class="form-group">
                            <label for="number">Enter your departure date</label>
                            <input type="date"
                                v-model="useManager.user.depart_date"
                                :min="today.toISOString().slice(0, 10)"
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
                            <input type="tel" @input="loadChild()" v-model.number="useManager.user.number_child" min="0"
                                max="4" :disabled="data?.status_input" id="password" name="password"
                                placeholder="Number of children (limit 4)" required>
                        </div>
                        <div class="contentchild" v-if="useManager.user.children.length > 0">
                            <!-- {{ useManager.children }} -->
                            <h5>Children informations</h5>
                            <div class="rowinput" v-for="(item, index) in useManager.user.children" :key="index">
                                <div class="form-group">
                                    <label for="age">Age (limit 23 ans)</label>
                                    <input type="number" :disabled="data?.status_input" v-model="item.age" id="age" min="0"
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
            <div class="col">
                <div class="card ">
                   <div class="head">
                    <h5> Summary </h5>

                    <div v-if="data?.status_input">
                            <template v-if="data?.status">
                            <div class="status approuve" v-if="data?.status == 'approved'">
                            approved
                            </div>
                            <div class="status reject" vl v-else>
                            rejected
                            </div>
                        </template>
                        <template v-else>
                            <div class="status pennding">
                            pending
                            </div>
                        </template>
                    </div>
                   </div>
                    <ul class="items">
                        <template v-if="data?.status_input">
                            <li class="item ">
                                 <div class="goupeStep">
                                    <div class="rond">1</div>
                                    <div class="trait"></div>
                                </div>
                                <div class="element element2">
                                <div class="li">
                                    <span>Travel with Family</span>
                                    <span>XOF {{separatorMillier(useManager.user.total_t_w_f)  }}</span>
                                </div>
                                <small class="info">
                                    {{ `(you=${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                                        +(spouse=${
                                        useManager.user?.marital_status =="yes" ? separatorMillier(useManager.calculate_amount(type[0]?.staff_category)) : 0})

                                        + (${useManager.user.children?.length}child * ${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                                    ` }}
                                </small>
                                </div>
                            </li>
                              <li class="item ">
                                <div class="goupeStep">
                                    <div class="rond">2</div>
                                    <div class="trait"></div>
                                </div>

                                <div class=" element element2">
                                        <div class="li">
                                            <span>Family initial accommodation</span>
                                            <span>XOF {{separatorMillier( useManager.user.total_f_i_a)}}</span>
                                        </div>
                                        <small class="info">
                                            {{ `(${useManager.user.room}room x ${separatorMillier(useManager.calculate_amount(type[2]?.staff_category)) }) x 7j` }}
                                        </small>
                                </div>
                            </li>

                            <li class="item">
                                 <div class="goupeStep">
                                    <div class="rond">3</div>
                                    <div class="trait"></div>
                                </div>
                               <div class="element">
                                <span>Personal effect Transportation</span>
                                <span>XOF   {{separatorMillier(useManager.user.total_p_e_t)  }}</span>
                               </div>
                            </li>





                            <li class="item ">
                                <div class="goupeStep">
                                    <div class="rond">4</div>
                                    <div class="trait"></div>
                                </div>
                               <div class="element">
                                    <span>Unforseen</span>
                                    <span>XOF   {{separatorMillier(useManager.user.total_u)  }}</span>
                               </div>
                            </li>
                            <li class="item ">
                                <div class="goupeStep">
                                <div class="rond">5</div>
                                <div class="trait"></div>
                            </div>
                              <div class="element">
                                  <span>Paliative for change in allowance</span>
                                <span>XOF   {{separatorMillier(useManager.user.total_p_c_a)  }}</span>
                              </div>
                            </li>

                        <li class="item total">
                            <span>Total</span>
                            <span>XOF   {{separatorMillier(useManager.user.total_amount)  }}</span>
                        </li>
                        </template>
                        <template v-else>
                            <li class="item rowItem">
                                <div class="info">
                                    <span>Travel with Family</span>
                                    <span>XOF {{separatorMillier(useManager.Total_T_W_F(type[0]))  }}</span>
                                </div>
                                <small class="info">
                                    {{ `(you=${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                                        +(spouse=${
                                        useManager.user.marital_status == "yes"? separatorMillier(useManager.calculate_amount(type[0]?.staff_category)) : 0})

                                        + (${useManager.user.children?.length}child * ${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                                    ` }}
                                </small>
                            </li>
                            <li class="item rowItem">
                                <div class="info">
                                    {{  }}
                                    <span>Family initial accommodation</span>
                                    <span>XOF {{separatorMillier(useManager.Total_F_I_A(type[2],useManager.user.children))  }}</span>
                                </div>
                                <!-- <img src="https://dashboard.quickshipper.app/icons/hor-line-thin.svg" alt=""> -->
                                <small class="info">
                                    {{ `(${useManager.Total_CHAMBRE(useManager.user.children)}room x ${separatorMillier(useManager.calculate_amount(type[2]?.staff_category)) }) x 7j` }}
                                </small>
                                <!-- {{ item?.staff_category }} -->
                            </li>


                            <li class="item rowItem">
                                <div class="info">
                                    <!-- {{type  }} -->
                                    <span>Personal effect Transportation</span>
                                    <span>XOF {{separatorMillier(useManager.calculate_amount(type[1]?.staff_category))  }}</span>
                                </div>
                            </li>
                             <li class="item rowItem">
                                <div class="info">
                                    <!-- {{type  }} -->
                                    <span>Unforseen</span>
                                    <span>XOF {{separatorMillier(useManager.calculate_amount(type[3]?.staff_category))  }}</span>
                                </div>
                            </li>

                            <li class="item rowItem">
                                <div class="info">
                                    <!-- {{type  }} -->
                                    <span>Paliative for change in allowance</span>
                                    <span>XOF {{separatorMillier(useManager.calculate_amount(type[4]?.staff_category))  }}</span>
                                </div>
                            </li>



                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>


<style lang="scss" >


</style>
