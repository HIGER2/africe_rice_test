import { computed, reactive, ref } from "vue"
import { toast } from 'vue3-toastify';


export const useManagerStore = () => {


    const user = reactive({
        "marital_status": "",
        "number_child": "",
        "depart_date":"",
        "children": [],
        "total_t_w_f" :0,
        "total_p_e_t" :0,
        "total_f_i_a" :0,
        "total_u" :0,
        "total_p_c_a" :0,
        "total_amount" :0,
    });

    // let employeeConnected.value?.category = "GSS1";

    let employeeConnected = ref();

    const calculate = (item) => {
        let amount = 0

        if (item?.id ==1) {
          amount =   Total_T_W_F(item)
        }
        if (item?.id ==2) {
          amount =  Total_P_E_T(item)
        }
        if (item?.id ==3) {
           amount = Total_F_I_A(item)
        }
        if (item?.id ==4) {
           amount = Total_U(item)
        }
        if (item?.id ==5) {
          amount =  Total_P_C_A(item)
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
            item?.staff_category.forEach(element => {
                if (element.name == employeeConnected.value?.category) {
                    amount += element.montant

                    if (user.marital_status == 'yes') {
                        amount +=Number(element.montant)
                    }

                    if (user.number_child > 0) {
                        amount +=(Number(element.montant) * Number(user.number_child))
                    }

                }
            });

            return amount
        }).value
    }

    const Total_P_E_T = (item) => {

        return computed(() => {
            let amount = 0
        item?.staff_category.forEach(element => {

            if (element.name == employeeConnected.value?.category) {
                if (element.device == "XOF") {
                amount += Number(element.montant);

                } else {
                    amount += (Number(element.montant) *  500);
                }
            }
        });

        return amount
        }).value
    }

     const Total_F_I_A = (item) => {

        return computed(() => {
            let amount = 0
        let room = 0;
            // console.log(item?.staff_category);
            item?.staff_category.forEach(element => {
                if (element.name == employeeConnected.value?.category) {
                    amount += (Number(element.montant) * 7);
                    if (user.children.length > 0) {
                        let nbF = 0;
                        let nbH = 0;
                        user.children.forEach(element => {
                            if (element.age && element.sex) {
                                if (element.age > 24) {
                                room ++;
                            } else {
                                if (element.sex == 'F') {
                                    nbF++
                                    if (nbF < 2 ) {
                                        room++
                                    } else {
                                        nbF = 0
                                    }
                                } else {

                                    nbH++
                                    if (nbH < 2 ) {
                                        room++
                                    } else {
                                        nbH = 0
                                    }
                                }
                            }
                            }



                        });
                    }
                    amount +=((Number(element.montant) * room) * 7)
                }
            });

        return amount
        }).value
    }
    const Total_U = (item) => {
        return computed(() => {
            let amount = 0
        item?.staff_category.forEach(element => {
            if (element.name == employeeConnected.value?.category) {
                if (element.name == employeeConnected.value?.category || element.name == "GS_6_9") {
                amount += Number(element.montant);
                } else {
                    amount += (Number(element.montant) *  500);
                }
            }
        })
        return amount
       }).value
    }

    const Total_P_C_A = (item) => {
        return computed(() => {
            let amount = 0
            item?.staff_category.forEach(element => {
                if (element.name == employeeConnected.value?.category) {
                    if (element.device == "XOF") {
                        amount += Number(element.montant);
                        } else {
                            amount += (Number(element.montant) *  500);
                        }
                }
            });

            return amount
        }).value
    }

    const Total_Amount = (type) => {

        return computed(() => {

            return type.reduce((total, item) => total + calculate(item), 0);

        }).value
    }

    const initial = (item) => {

        console.log(item);
        Object.assign(user,item)
    }

    const save = async(type) => {

        user.total_t_w_f=calculate(type[0])
        user.total_p_e_t=calculate(type[1])
        user.total_f_i_a = calculate(type[2])
        user.total_u =calculate(type[3])
        user.total_p_c_a = calculate(type[4])
        user.total_amount = Total_Amount(type)

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
        Total_Amount,
        save,
        calculate,
        separatorMillier,
        initial,
        employeeConnected,
        destroy
    }
}
