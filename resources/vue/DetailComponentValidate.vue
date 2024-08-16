
<script setup>
import { useManagerStore } from '../js/useManagerStore';
const { type ,data } = defineProps([ 'type','data']);


const useManager = useManagerStore();

const {
    Total_Amount,
    separatorMillier,
    save,
    calculate,
    initial,
    destroy
} = useManager;

</script>

<template>
    <div class="row-items">
        <ul class="items">
            <li class="item rowItem">
                    <div class="info">
                        <!-- {{ useManager.test }} -->
                        <!-- {{ useManager.user.marital_status }} -->
                        <span>Travel with Family</span>
                        <span> {{separatorMillier(useManager.user.total_t_w_f)  }} XOF</span>
                    </div>
                    <small class="info">
                        {{ `(Staff=${separatorMillier(useManager.calculate_amount(type[0]?.staff_categories))})
                            +(spouse=${
                            useManager.user?.marital_status =="yes" ? separatorMillier(useManager.calculate_amount(type[0]?.staff_categories)) : 0})

                            + (${useManager.user.children?.length} child * ${separatorMillier(useManager.calculate_amount(type[0]?.staff_categories))})
                        ` }}
                    </small>
            </li>
            <li class="item rowItem">
                <div class=" element element2">
                        <div class="li">
                            <span>Family initial accommodation</span>
                            <span> {{separatorMillier( useManager.user.total_f_i_a)}} XOF</span>
                        </div>
                        <small class="info">
                            {{ `(${useManager.user.Roomssss} Roomssss x ${separatorMillier(useManager.calculate_amount(type[2]?.staff_categories)) }) x 7 days` }}
                        </small>
                </div>
            </li>


            <li class="item rowItem">
                <div class="element">
                <span>Personal effect Transportation</span>
                <span>   {{separatorMillier(useManager.user.total_p_e_t)  }} XOF</span>
                </div>
            </li>
            <li class="item rowItem">
                <div class="element">
                    <span>Unforseen</span>
                    <span>   {{separatorMillier(useManager.user.total_u)  }} XOF</span>
                </div>
            </li>

            <!-- <li class="item rowItem">
                    <div class="element">
                        <span>Paliative for change in allowance</span>
                    <span>   {{separatorMillier(useManager.user.total_p_c_a)  }} XOF</span>
                    </div>
            </li> -->

            <li class="item total">
                <div class="element">
                    <span>Total</span>
                    <span>   {{ separatorMillier(useManager.user.total_amount)  }} XOF</span>
                </div>
            </li>
        </ul>
         <!-- <ul class="items">
            <li class="item rowItem">
                    <div class="info">

                        <span>Travel with Family</span>
                        <span> {{separatorMillier(useManager.user.total_t_w_f/useManager.currency)}} $</span>
                    </div>
                    <small class="info">
                        {{ `(Staff=${separatorMillier(useManager.calculate_amount(type[0]?.staff_categories))})
                            +(spouse=${
                            useManager.user?.marital_status =="yes" ? separatorMillier(useManager.calculate_amount(type[0]?.staff_categories)) : 0})
                            + (${useManager.user.children?.length} child * ${separatorMillier(useManager.calculate_amount(type[0]?.staff_categories))})
                        ` }}
                    </small>
            </li>
            <li class="item rowItem">
                <div class=" element element2">
                        <div class="li">
                            <span>Family initial accommodation</span>
                            <span> {{separatorMillier( useManager.user.total_f_i_a/useManager.currency)}} $</span>
                        </div>
                        <small class="info">
                            {{ `(${useManager.user.Roomssss} Roomssss x ${separatorMillier(useManager.calculate_amount(type[2]?.staff_categories)) }) x 7 days` }}
                        </small>
                </div>
            </li>


            <li class="item rowItem">
                <div class="element">
                <span>Personal effect Transportation</span>
                <span>   {{separatorMillier(useManager.user.total_p_e_t/useManager.currency)  }} $</span>
                </div>
            </li>
            <li class="item rowItem">
                <div class="element">
                    <span>Unforseen</span>
                    <span>   {{separatorMillier(useManager.user.total_u/useManager.currency)  }} $</span>
                </div>
            </li>

            <li class="item rowItem">
                    <div class="element">
                        <span>Paliative for change in allowance</span>
                    <span>   {{separatorMillier(useManager.user.total_p_c_a/useManager.currency) }} $</span>
                    </div>
            </li>

            <li class="item total">
                <div class="element">
                    <span>Total</span>
                    <span>   {{separatorMillier(useManager.user.total_amount/useManager.currency)}} $</span>
                </div>
            </li>
        </ul> -->
    </div>

</template>


<style lang="scss" scoped>

</style>
