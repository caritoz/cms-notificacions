import {defineStore} from "pinia";
import {cloneDeep} from "lodash";

export const useNotificationStore = defineStore('NotificationStore', {
    historyEnabled: true,
    state: () => {
        return {
            //notifications: useLocalStorage("NotificationStore:notifications",[]),
            notifications: [],
            test: "hello word",
            isChannel: false
        }
    },
    getters: {// computed
        count: (state) => state.notifications.length,
        isEmpty: (state) => state.notifications.length === 0,
        total: (state) => state.notifications.reduce( (p, c) => p + c.price, 0 ),
    },
    actions: {
        addFetchingNotification(payload){
            this.notifications.unshift(payload); // push
        },
        updateFetchingNotification(payload){
            let items = cloneDeep(this.notifications)
            const index = items.findIndex(user => user.id === payload.id);
            this.notifications = [ ...items.slice(0, index), payload, ...items.slice(index + 1) ];
        },
        removeFetchingNotification(payload){
            this.notifications.splice(this.notifications.findIndex(notification => notification.id === payload.id), 1);
        },
        fill( items ){
            this.notifications = items
        },
        deleteNotification(item) {
            console.log('Deleting notification...')
            this.removeFetchingNotification(item)
            axios.delete(`api/notifications/delete/${item.id} `)
                .finally( () =>  console.log( 'Deleted notification' ) )
        },
        markAsRead(item){
            console.log('Marking notification...')
            axios
                .put(  `/api/notifications/mark-as-read`,  {id: item.id})
                .then(( {data} ) => {
                    this.updateFetchingNotification(data);
                })
                .finally( () =>  console.log('Marked as read notification') )
                .catch((error) => {
                    console.log(error.res.data);
                });

        },
        createUserChannel(user){
            // subscribe channel
            if( ! this.isChannel ){
                Echo.private(`App.Models.User.${user.id}`)
                    .notification((notification) => {
                        console.log(notification)
                        this.addFetchingNotification(notification)
                    });
                this.isChannel = true
                console.log('Created notifications channel!');
            }
        }
    }
})
