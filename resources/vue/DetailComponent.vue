
<script setup>
import { useManagerStore } from '../js/useManagerStore';
const { type } = defineProps([ 'type' ]);


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
                    <span>{{separatorMillier(useManager.Total_T_W_F(type[0]))}} XOF</span>
                </div>
                <small class="info">
                    {{ `(you=${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                        +(spouse=${
                        useManager.user.marital_status == "yes"? separatorMillier(useManager.calculate_amount(type[0]?.staff_category)) : 0})

                        + (${useManager.user.children?.length} child * ${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                    ` }}
                </small>
            </li>
            <li class="item rowItem">
                <div class="info">
                    <span>Family initial accommodation</span>
                    <span>{{separatorMillier(useManager.Total_F_I_A(type[2],useManager.user.children))}} XOF</span>
                </div>
                <!-- <img src="https://dashboard.quickshipper.app/icons/hor-line-thin.svg" alt=""> -->
                <small class="info">
                    {{ `(${useManager.Total_CHAMBRE(useManager.user.children)} Room x ${separatorMillier(useManager.calculate_amount(type[2]?.staff_category)) }) x 7 days` }}
                </small>
                <!-- {{ item?.staff_category }} -->
            </li>


            <li class="item rowItem">
                <div class="info">
                    <span>Personal effect Transportation</span>
                    <span>{{separatorMillier(useManager.calculate_amount(type[1]?.staff_category))}} XOF</span>
                </div>
            </li>
                <li class="item rowItem">
                <div class="info">
                    <span>Unforseen</span>
                    <span>{{separatorMillier(useManager.calculate_amount(type[3]?.staff_category))}} XOF</span>
                </div>
            </li>

            <li class="item rowItem">
                <div class="info">
                    <span>Paliative for change in allowance</span>
                    <span> {{separatorMillier(useManager.calculate_amount(type[4]?.staff_category))}} XOF</span>
                </div>
            </li>

            <li class="item total">
                <div class="element">
                    <span>Total</span>
                    <span>   {{separatorMillier(useManager.Total_Amount(type))   }} XOF</span>
                </div>

            </li>
    </ul>
     <ul class="items">
        <li class="item rowItem">
                <div class="info">
                    <!-- {{ useManager.test }} -->
                    <!-- {{ useManager.user.marital_status }} -->
                    <span>Travel with Family</span>
                    <span>{{separatorMillier(useManager.Total_T_W_F(type[0])/500)}} $</span>
                </div>
                <small class="info">
                    {{ `(you=${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                        +(spouse=${
                        useManager.user.marital_status == "yes"? separatorMillier(useManager.calculate_amount(type[0]?.staff_category)) : 0})

                        + (${useManager.user.children?.length} child * ${separatorMillier(useManager.calculate_amount(type[0]?.staff_category))})
                    ` }}
                </small>
            </li>
            <li class="item rowItem">
                <div class="info">
                    <span>Family initial accommodation</span>
                    <span>{{separatorMillier(useManager.Total_F_I_A(type[2],useManager.user.children)/500)}} $</span>
                </div>
                <!-- <img src="https://dashboard.quickshipper.app/icons/hor-line-thin.svg" alt=""> -->
                <small class="info">
                    {{ `(${useManager.Total_CHAMBRE(useManager.user.children)}room x ${separatorMillier(useManager.calculate_amount(type[2]?.staff_category)) }) x 7days` }}
                </small>
                <!-- {{ item?.staff_category }} -->
            </li>


            <li class="item rowItem">
                <div class="info">
                    <span>Personal effect Transportation</span>
                    <span> {{separatorMillier(useManager.calculate_amount(type[1]?.staff_category)/500)}} $</span>
                </div>
            </li>
                <li class="item rowItem">
                <div class="info">
                    <span>Unforseen</span>
                    <span> {{separatorMillier(useManager.calculate_amount(type[3]?.staff_category) / 500)}} $</span>
                </div>
            </li>

            <li class="item rowItem">
                <div class="info">
                    <span>Paliative for change in allowance</span>
                    <span> {{separatorMillier(useManager.calculate_amount(type[4]?.staff_category)/ 500)}} $</span>
                </div>
            </li>
            <li class="item total">
                <div class="element">
                    <span>Total</span>
                <span>   {{separatorMillier(useManager.Total_Amount(type) / 500)   }} $</span>
                </div>
            </li>
    </ul>
    </div>

</template>


<style lang="scss" scoped>

</style>
