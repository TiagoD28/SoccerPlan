// import React, { createContext, useContext, useState, useEffect } from "react";
// import sendDataToApi from "../../getPostApi/sendDataToApi";
// import { useUser } from "./UserContext";

// // Create the context
// const EventsContext = createContext();

// // Create a provider component
// export const EventsProvider = ({ children }) => {
//   const [pastEvents, setPastEvents] = useState([]);
//   const [nextEvents, setNextEvents] = useState([]);
//   const { dataUser } = useUser();

//   useEffect(() => {
//     const fetchData = async () => {
//       try {
//         // Fetch past events
//         const pastEventsResponse = await sendDataToApi('Events', 'getPastEvents', {"idUser": dataUser.idUser, "idClub": dataUser.idClub});
//         if (pastEventsResponse.status === '200') {
//           const sortedPastEvents = pastEventsResponse.data.sort((a, b) => new Date(a.startDate) - new Date(b.startDate));
//           setPastEvents(sortedPastEvents);
//         }

//         // Fetch next events
//         const nextEventsResponse = await sendDataToApi('Events', 'getNextEvents', {"idUser": dataUser.idUser, "idClub": dataUser.idClub});
//         if (nextEventsResponse.status === '200') {
//           const sortedNextEvents = nextEventsResponse.data.sort((a, b) => new Date(a.startDate) - new Date(b.startDate));
//           setNextEvents(sortedNextEvents);
//         }
//       } catch (error) {
//         console.error('Error fetching events:', error.message);
//       }
//     };

//     fetchData();
//   }, [dataUser.idUser, dataUser.idClub]);

//   return (
//     <EventsContext.Provider value={{ pastEvents, nextEvents }}>
//       {children}
//     </EventsContext.Provider>
//   );
// };

// // Create a custom hook to use the events context
// export const useEvents = () => {
//   const context = useContext(EventsContext);
//   if (!context) {
//     throw new Error("useEvents must be used within an EventsProvider");
//   }
//   return context;
// };
