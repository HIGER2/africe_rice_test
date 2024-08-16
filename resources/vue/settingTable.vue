
<script setup>
import { onMounted, ref } from 'vue';
import { useManagerStore } from '../js/useManagerStore';

const { type_allowence ,currency} = defineProps(['currency', 'type_allowence' ]);

const useManager = useManagerStore();

const isLoading = ref(false);

// console.log(today.toISOString());
const onSave = async (type) => {

    let confirmS = confirm('are you sure you want to save')
    if (confirmS) {
        isLoading.value = true
    await useManager.saveTypeAllowance(useManager.type_allowance)
    isLoading.value = false
    }

 }

onMounted(() => {

    if (type_allowence) {
        useManager.type_allowance = [ ...type_allowence ]
        useManager.currency = currency.value
    }
});
</script>


<template>
    <div>
        <!-- {{ useManager.type_allowance }} -->
        <!-- {{ type_allowence[0] }} -->
        <form action="" @submit.prevent="onSave">
          <!-- {{ useManager.currency  }} -->
                <div class="card2">
                    <div class="form-group">
                        <label for="number">Update exchange rate</label>
                        <input type="number" name="date"
                        v-model="useManager.currency "
                        placeholder="Update exchange rate" required>
                    </div>
                </div>
                <h5>Update Staff Category Amount</h5>
            <div class="card">
                <div class="content-table">
                    <table>
                    <!-- <thead>
                        <tr>
                            <th>Type of Allowance</th>
                            <th>Staff Category</th>
                        </tr>
                    </thead> -->
                   <tbody>
                     <tr v-for="(items, parentIndex) in type_allowence" :key="parentIndex">
                        <td>{{ items?.name }}</td>
                        <td class="staff">
                             <div class="element" v-for="(item, index) in items?.staff_categories" :key="index" >
                                <div>{{ item?.name }}</div>
                            <div>
                                <input type="number" min="0"
                                :parentIndex="parentIndex"
                                :childIndex="index"
                                keyIndex="amount"
                                :value="item.amount"
                                @input="(event)=>useManager.editing(event.target)"
                                >
                            </div>
                                <div >
                                 <select name="" id=""
                                 :parentIndex="parentIndex"
                                :childIndex="index"
                                keyIndex="currency"
                                :value="item?.currency"
                                @change="(event)=>useManager.editing(event.target)"
                                >
                                <option value="XOF">XOF</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                            </div>
                        </td>
                     </tr>
                   </tbody>
                </table>
                </div>
            </div>

            <button class="savebutton">
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
                    Save
                </span>
            </button>
        </form>
    </div>
</template>

<style lang="scss" scoped>

</style>
