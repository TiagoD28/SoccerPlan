
import React, { useEffect, useState, useRef } from "react";
// import { useState } from "react";
import {View, Text, StyleSheet, TouchableOpacityComponent, TouchableOpacity, TurboModuleRegistry} from 'react-native';
import sendDataToApi from "../getPostApi/sendDataToApi";
import { TestTube } from "lucide-react-native";
import { Icon } from "@rneui/themed";
import { useUser } from "./Context/UserContext";
import { useEvents } from "./Context/EventsContext";
// import { da } from "date-fns/locale";

export default function CardComponents(){
// export default function CardComponents({userLogged}){
    const { dataUser } = useUser();
    // const { events, setEvents} = useState();
    const [isPastEvents, setIsPastEvents] = useState(false);
    const [isNextEvents, setIsNextEvents] = useState(false);
    const [pastEvents, setPastEvents] = useState([]);
    const [nextEvents, setNextEvents] = useState([]);
    // const [idClubUpdated, setIdClubUpdated] = useState(0);
    // const [index, setIndex] = useState(0);
    // const [initialCallMade, setInitialCallMade] = useState(false);
    // console.log("Cards: ",cards);

    const checkPastEvents = async () => {
        try {
            // if(cards != null){
                // console.log("Card components: ",dataUser.idUser);
                // const apiResponse = await sendDataToApi('Events', 'getPastEvents', {"idUser": dataUser.idUser, "idClub": dataUser.idClub});
                const apiResponse = await sendDataToApi('Events', 'getPastEvents', {"idClub": dataUser.idClub, "idTeam": dataUser.idTeam});
                // console.log('Api response datas: ',apiResponse.data);
                console.log("Past Events: ",apiResponse.data);
                if (apiResponse.status === '400') {
                    // console.log('There is no Past Events!');
                    if(isPastEvents === true){
                        setIsPastEvents(false);
                    }
                } else if(apiResponse.status === '200') {
                    // console.log(apiResponse.message);
                    setIsPastEvents(true);
                    // Sort the past events based on startDate
                    const uniquePastEvents = [...new Map(apiResponse.data.map(item => [item.idEvent, item])).values()]; // this is to avoid display two times the same event
                    const sortedPastEvents = uniquePastEvents.sort((a, b) => { // sort the past events
                        const dateA = formatDateToSort(a.startDate);
                        const dateB = formatDateToSort(b.startDate);
                        return dateA.localeCompare(dateB);
                    });
                    setPastEvents(sortedPastEvents);
                }
                console.log("Past Events Sorted: ",apiResponse.data);
            // }
            
        } catch (error) {
            console.error('Error in checkPastEvents:', error.message);
        }
    };

    const checkNextEvents = async () => {
        // if(cards != null){
            try {
                // await updateIdClub();
                const apiResponse = await sendDataToApi('Events', 'getNextEvents', {"idUser": dataUser.idUser, "idClub": dataUser.idClub});
                // const apiResponse = await sendDataToApi('Events', 'getNextEvents', {"idUser": userLogged.idUser, "idClub": userLogged.idClub});
                if (apiResponse.status === '400') {
                    console.log('There is no Next Events!');
                    if(isNextEvents === true){
                        setIsNextEvents(false);
                    }
                } else if(apiResponse.status === '200') {
                    setIsNextEvents(true);
                    //  // Sort the past events based on startDate
                    const uniqueNextEvents = [...new Map(apiResponse.data.map(item => [item.idEvent, item])).values()]; // this is to avoid display two times the same event
                    const sortedNextEvents = uniqueNextEvents.sort((a, b) => { // sort the past events
                        const dateA = formatDateToSort(a.startDate);
                        const dateB = formatDateToSort(b.startDate);
                        return dateA.localeCompare(dateB);
                    });

                    // const sortedNextEvents = apiResponse.data.sort((a, b) => {
                    //     const dateA = formatDateToSort(a.startDate);
                    //     const dateB = formatDateToSort(b.startDate);
                    //     return dateA.localeCompare(dateB);
                    //   });

                    setNextEvents(sortedNextEvents);
                }
            } catch (error) {
                console.error('Error in checkNextEvents:', error.message);
            };       
            console.log("Next Events: ",nextEvents);
        // }
    };

    useEffect(() => {
        // Sort past events
        const sortedPastEvents = pastEvents.sort((a, b) => {
            const dateA = formatDateToSort(a.startDate);
            const dateB = formatDateToSort(b.startDate);
            return dateA.localeCompare(dateB);
        });
        setPastEvents(sortedPastEvents);
    
        // Sort next events
        const sortedNextEvents = nextEvents.sort((a, b) => {
            const dateA = formatDateToSort(a.startDate);
            const dateB = formatDateToSort(b.startDate);
            return dateA.localeCompare(dateB);
        });
        setNextEvents(sortedNextEvents);
    }, [pastEvents, nextEvents]);

    useEffect(() => {
        checkPastEvents();
        checkNextEvents();
    }, []);

    const formatDateToSort = (dateString) => {
        const parts = dateString.split('-');
        // console.log(`${parts[2]}-${parts[1]}-${parts[0]}`);
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
      };

    const formatDate = (dateString) => {
        // Split the date string into parts
        const parts = dateString.split('-');

        // Create a new date string in the format YYYY/MM/DD
        const formattedDateString = `${parts[2]}-${parts[1]}-${parts[0]}`;

        // Parse the formatted date string and create a Date object
        const dateObject = new Date(formattedDateString);

        // Get day, month, and year from the Date object
        const day = dateObject.getDate();
        const month = dateObject.toLocaleString('defaul', {month: 'short'}); // Months are zero-based, so add 1
        const year = dateObject.getFullYear();

        // Get the day of the week
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const dayOfWeek = daysOfWeek[dateObject.getDay()];

        return [dayOfWeek, day, month, year];
    }

    
    {/* <Text key={`date-${index}`}>{formatDate(card.startDate)[3]}</Text> */}
    return(
        <>
            {isPastEvents ? (
                <View style={styles.container}>
                    <Text style={styles.title}>Past Events:</Text>
                     {pastEvents.map((card, index) => (
                        <React.Fragment key={`fragment-${index}`}>
                            
                            <View key={`card-${index}`} style={styles.card}>
                                <View key={`cardLeft-${index}`} style={styles.cardLeft}>
                                    <Text style={styles.txtDateL}>{formatDate(card.startDate)[0]}</Text>
                                    <Text style={styles.txtDateL}>{formatDate(card.startDate)[1]}</Text>
                                    <Text style={styles.txtDateL}>{formatDate(card.startDate)[2]}.</Text>
                                    <Text style={styles.txtTimeL}>{card.meetTime}</Text>
                                </View>
                                <View style={styles.cardRight}>
                                    <Text style={styles.titleR}>{card.idEvent}</Text>
                                    <Text style={styles.txtR}>{card.typeEvent}</Text>
                                </View>
                            </View>
                        </React.Fragment>
                    )
                    )}
                </View>
            ) : (
                <>
                     <View style={styles.container}>
                        <Text style={styles.title}>Past Events:</Text>
                        <Text>There is no Past Events!</Text>
                    </View>
                </>
            )}

             {isNextEvents ? (
                 <View style={styles.container}>
                 <Text style={styles.title}>Next Events:</Text>
                 {nextEvents.map((card, index) => (
                    <React.Fragment key={`fragment-${index}`}>
                        <View key={`card-${index}`} style={styles.card}>
                            <View key={`cardLeft-${index}`} style={styles.cardLeft}>
                                <Text style={styles.txtDateL}>{formatDate(card.startDate)[0]}</Text>
                                <Text style={styles.txtDateL}>{formatDate(card.startDate)[1]}</Text>
                                <Text style={styles.txtDateL}>{formatDate(card.startDate)[2]}.</Text>
                                <Text style={styles.txtTimeL}>{card.meetTime}</Text>
                            </View>
                            <View style={styles.cardRight}>
                                <Text style={styles.titleR}>{card.idEvent}</Text>
                                <Text style={styles.txtR}>{card.typeEvent}</Text>
                            </View>
                        </View>
                    </React.Fragment>
                 ))}
             </View>
            ) : (
                <View style={styles.container}>
                    <Text style={styles.title}>Next Events:</Text>
                    <Text>There is no Next Events!</Text>
                </View>
            )}
        </>
    )
}



const styles = StyleSheet.create({
    container: {
        flexDirection: 'column',
        flexWrap: 'wrap',
        justifyContent: 'space-between',
        marginLeft: '5%',
        marginTop: 30
    },
    title: {
        fontSize: 20,
        fontWeight: 'bold',

    },
    card: {
        flexDirection: 'row',
        width: '95%', // Adjust the width as needed based on your design
        height: 125,
        backgroundColor: '#041b2b',
        borderRadius: 8,
        shadowColor: '#041b2b',
        shadowOffset: {width: 3, height: 4},
        shadowOpacity: 0.4,
        shadowRadius: 2,
        marginVertical: 8,
    },
    cardLeft: {
        flexDirection: 'column',
        width: '25%',
        justifyContent: 'center',
        alignItems: 'center',
        borderRightWidth: 1,
        borderRightColor: 'gray',

    },
    cardRight: {
        width: '70%',
    },
    txtDateL:{
        fontSize: 15,
        color: 'white',
        fontWeight: 'bold'
    },
    txtTimeL: {
        fontSize: 13,
        color: 'white',
        marginTop: 10
    },
    titleR:{
        fontSize: 16,
        fontWeight: 'bold',
        color: 'white',
        padding: 10
    },   
    txtR:{
        fontSize: 15,
        color: 'white',
        marginLeft: 10
    },   
  });




// import React, { useEffect, useState } from "react";
// import { View, Text, StyleSheet } from 'react-native';
// import sendDataToApi from "../getPostApi/sendDataToApi";
// import { useUser } from "./Context/UserContext";

// export default function CardComponents() {
//   const { dataUser } = useUser();
//   const [isPastEvents, setIsPastEvents] = useState(false);
//   const [isNextEvents, setIsNextEvents] = useState(false);
//   const [pastEvents, setPastEvents] = useState([]);
//   const [nextEvents, setNextEvents] = useState([]);

//   const checkPastEvents = async () => {
//     try {
//       const apiResponse = await sendDataToApi('Events', 'getPastEvents', {"idClub": dataUser.idClub, "idTeam": dataUser.idTeam});
//       if (apiResponse.status === '400') {
//         if (isPastEvents) {
//           setIsPastEvents(false);
//         }
//       } else if (apiResponse.status === '200') {
//         setIsPastEvents(true);
//         const sortedPastEvents = apiResponse.data.sort((a, b) => {
//           const dateA = formatDate(a.startDate);
//           const dateB = formatDate(b.startDate);
//           return dateA.localeCompare(dateB);
//         });
//         setPastEvents(sortedPastEvents);
//       }
//     } catch (error) {
//       console.error('Error in checkPastEvents:', error.message);
//     }
//   };

//   const checkNextEvents = async () => {
//     try {
//       const apiResponse = await sendDataToApi('Events', 'getNextEvents', {"idUser": dataUser.idUser, "idClub": dataUser.idClub});
//       if (apiResponse.status === '400') {
//         if (isNextEvents) {
//           setIsNextEvents(false);
//         }
//       } else if (apiResponse.status === '200') {
//         setIsNextEvents(true);
//         const sortedNextEvents = apiResponse.data.sort((a, b) => {
//           const dateA = formatDate(a.startDate);
//           const dateB = formatDate(b.startDate);
//           return dateA.localeCompare(dateB);
//         });
//         setNextEvents(sortedNextEvents);
//       }
//     } catch (error) {
//       console.error('Error in checkNextEvents:', error.message);
//     }
//   };

//   useEffect(() => {
//     checkPastEvents();
//     checkNextEvents();
//   }, []);

//   const formatDate = (dateString) => {
//     const parts = dateString.split('-');
//     return `${parts[2]}-${parts[1]}-${parts[0]}`;
//   };

//   return (
//     <>
//       {isPastEvents ? (
//         <View style={styles.container}>
//           <Text style={styles.title}>Past Events:</Text>
//           {pastEvents.map((card, index) => (
//             <View key={`card-${index}`} style={styles.card}>
//               <Text>{formatDate(card.startDate)}</Text>
//               <Text>{card.typeEvent}</Text>
//             </View>
//           ))}
//         </View>
//       ) : (
//         <View style={styles.container}>
//           <Text style={styles.title}>Past Events:</Text>
//           <Text>There is no Past Events!</Text>
//         </View>
//       )}

//       {isNextEvents ? (
//         <View style={styles.container}>
//           <Text style={styles.title}>Next Events:</Text>
//           {nextEvents.map((card, index) => (
//             <View key={`card-${index}`} style={styles.card}>
//               <Text>{formatDate(card.startDate)}</Text>
//               <Text>{card.typeEvent}</Text>
//             </View>
//           ))}
//         </View>
//       ) : (
//         <View style={styles.container}>
//           <Text style={styles.title}>Next Events:</Text>
//           <Text>There is no Next Events!</Text>
//         </View>
//       )}
//     </>
//   );
// }

// const styles = StyleSheet.create({
//   container: {
//     flexDirection: 'column',
//     flexWrap: 'wrap',
//     justifyContent: 'space-between',
//     marginLeft: '5%',
//     marginTop: 30
//   },
//   title: {
//     fontSize: 20,
//     fontWeight: 'bold',
//   },
//   card: {
//     flexDirection: 'row',
//     width: '95%',
//     height: 50,
//     backgroundColor: '#041b2b',
//     borderRadius: 8,
//     shadowColor: '#041b2b',
//     shadowOffset: {width: 3, height: 4},
//     shadowOpacity: 0.4,
//     shadowRadius: 2,
//     marginVertical: 8,
//     justifyContent: 'space-between',
//     padding: 10,
//   },
// });



