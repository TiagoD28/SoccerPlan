import React, { createContext, useContext, useState } from 'react';

const UserContext = createContext();

export const UserProvider = ({ children }) => {
  const [dataUser, setDataUser] = useState(null);

  const setLoggedUser = (data) => {
    // setUserData(data);
    // if (data.idCoach !== null){
        // If data is provided, update specific properties
    if (data) {
        setDataUser((prevData) => ({ ...prevData, ...data }));
        // setIsLogged(true);
      } else {
        // If no data is provided, set the user data to null
        setDataUser(null);
        // setIsLogged(false);
      }
    // }
    
  };

  return (
    <UserContext.Provider value={{ dataUser, setLoggedUser }}>
      {children}
    </UserContext.Provider>
  );
};

export const useUser = () => {
  const context=  useContext(UserContext);

  if (context == null) {
    console.log('Context empty');
  }
  return context;
};