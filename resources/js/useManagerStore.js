import { computed, reactive } from "vue"
import { toast } from 'vue3-toastify';


export const useManagerStore = () => {


    const user = reactive({
        "marital_status": "",
        "number_child": "",
        "children": [],
    });

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

       return computed(() => {
             let amount = 0
            item?.staff_category.forEach(element => {
                if (element.name == "GS_1_5") {
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

            if (element.name == "GS_1_5") {
                if (element.name == "GS_1_5") {
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
                if (element.name == "GS_1_5") {
                    amount += Number(element.montant);
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

                    amount +=(Number(element.montant) * room)
                }
            });

        return amount
        }).value
    }
    const Total_U = (item) => {
        return computed(() => {
            let amount = 0
        item?.staff_category.forEach(element => {
            if (element.name == "GS_1_5") {
                amount +=Number(element.montant);
            }
        })
        return amount
       }).value
    }

    const Total_P_C_A = (item) => {
        return computed(() => {
            let amount = 0
            item?.staff_category.forEach(element => {
                if (element.name == "GS_1_5") {
                    amount += Number(element.montant);
                }
            });

            return amount
        }).value
    }

    const Total_Amount = (type) => {
              return type.reduce((total, item) => total + calculate(item), 0);
    }

    const initial = (item) => {

        console.log(item);
        Object.assign(user,item)
    }

    const save = async() => {



     await window.axios.post(`/save`, user)
         .then(async(response) => {

             Object.assign(user,response?.data?.data)
                toast.success('operation completed successfully', {
                position: toast.POSITION.TOP_CENTER,
                // transition:customAnimation
                });
             setTimeout(() => {
             location.reload()
             }, 1000);

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
        initial
    }
}
