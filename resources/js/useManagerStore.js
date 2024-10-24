import { computed, reactive, ref } from "vue"
import { toast } from 'vue3-toastify';
import { defineStore } from 'pinia';


export const useManagerStore = defineStore('manager',() => {

    const test = ref(1)

    let type_allowance = ref([]);
    const user = reactive({
        "marital_status": "",
        "number_child": "",
        "depart_date": "",
        "taking_date":"",
        "children": [],
        "room": 1,
        "amount":0,
        "total_t_w_f" :0,
        "total_p_e_t" :0,
        "total_f_i_a" :0,
        "total_u" :0,
        "total_p_c_a" :0,
        "total_amount" :0,
    });



    // let employeeConnected.value?.category = "GSS1";
    let chilExiste = [];
    let nb_f = 0;
    let nb_h = 0;
    let nb_all = 0;

    let employeeConnected = ref();
    let currency = ref(0);

    const editing = (target) => {
        let childIndex =target.getAttribute('childIndex')
        let parentIndex = target.getAttribute('parentIndex')
        let keyIndex = target.getAttribute('keyIndex')

        if (!target.value.trim() == "") {
            type_allowance.value[ parentIndex ].staff_categories[ childIndex ][ keyIndex ] = target.value.trim()
        }

        // console.log(chiildIndex,parentIndex);
    }
    const calculate_amount = (item) => {
        let amount = 0
        item?.forEach(element => {
            if (element.name == employeeConnected.value?.category) {

                if (element.currency == "XOF") {
                    amount += element.amount

                } else {
                     amount += (Number(element.amount) *  currency.value);
                }

                }
            });

        return amount
    }

    const calculate = (item) => {
        let amount = 0

        if (employeeConnected.value?.show) {
            if (item?.id ==1) {
            amount =   Total_T_W_F(item)
            }

            if (item?.id ==3) {
            amount = Total_F_I_A(item,user.children)
            }
            if (item?.id ==4) {
            amount = Total_U(item)
            }
            if (item?.id ==5) {
            //   amount =  Total_P_C_A(item)
            }
        }
        if (item?.id ==2) {
          amount =  Total_P_E_T(item)
        }

        return amount
    }


 const separatorMillier = (montant) => {

const options = {
    useGrouping: true,
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
    };
    let result = montant?.toLocaleString('fr-FR', options).replace(/\s/g, ' ');

    return result
  }


    const Total_T_W_F = (item) => {
        // console.log(item);

        return computed(() => {
            let amount = 0
            item?.staff_categories.forEach(element => {
                if (element.name == employeeConnected.value?.category) {
                    amount += element.amount

                    if (user.marital_status == 'yes') {
                        amount +=Number(element.amount)
                    }

                    if (user.number_child > 0) {
                        amount +=(Number(element.amount) * Number(user.number_child))
                    }

                }
            });

            return amount
        }).value
    }


    const Total_P_E_T = (item) => {

        return computed(() => {
            let amount = 0
        item?.staff_categories.forEach(element => {

            if (element.name == employeeConnected.value?.category) {
                if (element.currency == "XOF") {
                amount += Number(element.amount);

                } else {
                    amount += (Number(element.amount) *  Number(currency.value));
                }
            }
        });

        return amount
        }).value
    }



    const Total_CHAMBRE = (children) => {

        return computed(() => {
            // let amount = calculate_amount(item?.staff_categories)
            let chambres = 1;
            let enfants15a23 = 0
            let enfantsMoins15  = 0
            let enfantsMoins23 = 0
            let enfantsPlus23 = 0

            if (children?.length > 0) {

                enfantsPlus23 = children.filter((enfant) => {
                    if (enfant.age && enfant.sex) {
                        return enfant.age > 23
                    }
                });

                enfantsMoins23 = children.filter((enfant) => {
                    if (enfant.age && enfant.sex) {
                        return enfant.age <= 23
                    }
                });

                if (enfantsMoins23.length > 0) {
                    enfants15a23 = enfantsMoins23.filter(enfant => enfant.age >= 15 && enfant.age <= 23);
                    enfantsMoins15 = enfantsMoins23.filter(enfant => enfant.age < 15);
                }

                // Attribuer des chambre aux enfants de plus de 23 ans


                // Attribuer des chambres aux enfants de moins de 15 ans, peu importe le sexe
                if (enfantsMoins15.length > 0) {
                    let groupe = 0;
                    enfantsMoins15.forEach(element => {
                        groupe++
                        if (groupe < 2) {
                            chambres++
                        } else {
                            groupe = 0
                        }
                    });
                }

                    // Attribuer des chambres aux enfants de 16 à 23 ans en fonction du sexe

                if (enfants15a23.length > 0) {
                    let groupeF=0;
                    let groupeM = 0;
                    if (enfants15a23.length >=2) {
                            chambres+=2
                    }
                    if (enfants15a23.length == 1) {
                            chambres++
                    }
                    // enfants15a23.forEach(element => {
                    //     if (element?.sex && element?.age) {
                    //         if (element.sex == "F") {
                    //         groupeF++
                    //         if (groupeF < 2) {
                    //             chambres +=groupeF
                    //         } else {
                    //             groupeF= 0
                    //         }
                    //     }

                    //     if (element.sex == "M") {
                    //         groupeM++
                    //         if (groupeM < 2) {

                    //             chambres++
                    //         } else {
                    //             groupeM= 0
                    //         }
                    //     }
                    //     }


                    // });
                }


            }
            return chambres
        }).value
    }


const Total_F_I_A = (item,children) => {
        return computed(() => {
            let amount = calculate_amount(item?.staff_categories)

            let chambres = Total_CHAMBRE(children)

            return (chambres * amount) * 7
        }).value
    }



    const Total_U = (item) => {


        return computed(() => {
         let amount = calculate_amount(item?.staff_categories)

        return amount
       }).value
    }

    const Total_P_C_A = (item) => {
        return computed(() => {
         let amount = calculate_amount(item?.staff_categories)
            return amount
        }).value
    }

    const Total_Amount = (type) => {

        return computed(() => {
            return type.reduce((total, item) => total + calculate(item), 0);

        }).value
    }

    const initial = (item) => {
        Object.assign(user,item)

        // if (item.status_input) {
        // Object.assign(user,item)
        // }
    }

    const save = async(type) => {

            user.total_p_e_t=calculate(type[1])
        // user.total_p_c_a = calculate(type[4])
        if (employeeConnected.value?.show) {
            user.total_u = calculate(type[ 3 ])
            user.total_t_w_f = calculate(type[ 0 ])
            user.total_f_i_a = Total_F_I_A(type[2],user.children)



        }
        user.total_amount = Total_Amount(type)
        user.room = Total_CHAMBRE(user.children)

        scrollTo(0,0)

     await window.axios.post(`/save`, user)
         .then(async(response) => {

             Object.assign(user,response?.data?.data)
                toast.success('operation completed successfully', {
                position: toast.POSITION.TOP_CENTER,
                // transition:customAnimation
                });

                setTimeout(() => {
                location.reload()
                }, 500);

            })
         .catch(error => {
              toast.error(error?.response?.data?.message, {
                position: toast.POSITION.TOP_CENTER,
                // transition:customAnimation
              });
             console.log(error?.response?.data?.message);
            //  console.log(error?.response?.data?.data?.message);
            })
            .finally(() => {
            })
            ;
    }


    const saveTypeAllowance = async(item) => {
        let data = {
            currency: currency.value,
            type_allowance:item
        }
     await window.axios.post(`/setting`, data)
         .then(async(response) => {

             console.log(response);
            //  Object.assign(user,response?.data?.data)
                toast.success('operation completed successfully', {
                position: toast.POSITION.TOP_CENTER,
                // transition:customAnimation
                });
             setTimeout(() => {
             location.reload()
             }, 500);

            //  console.log(response);
            })
         .catch(error => {
              toast.error(error?.response?.data?.message, {
                position: toast.POSITION.TOP_CENTER,
                // transition:customAnimation
              });
             console.log(error?.response?.data?.message);
            //  console.log(error?.response?.data?.data?.message);
            })
            .finally(() => {
            })
            ;
    }


    const destroy = async(id) => {



     await window.axios.get(`/destroy/${id}`)
         .then(async(response) => {
             setTimeout(() => {
             location.reload()
             }, 100);
             console.log(response);
            })
         .catch(error => {

            })
            .finally(() => {
            })
            ;
    }


    return {
        user,
        Total_T_W_F,
        Total_P_E_T,
        Total_F_I_A,
        Total_U,
        Total_P_C_A,
        calculate_amount,
        Total_Amount,
        Total_CHAMBRE,
        save,
        calculate,
        separatorMillier,
        initial,
        employeeConnected,
        destroy,
        test,
        type_allowance,
        editing,
        saveTypeAllowance,
        currency
    }
})
