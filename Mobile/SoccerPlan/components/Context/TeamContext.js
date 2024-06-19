import React, { createContext, useState, useContext } from 'react';

const TeamContext = createContext();

export const TeamProvider = ({ children }) => {
  const [homeTeam, setHomeTeam] = useState(true);
  const [categoryList, setCategoryList] = useState(false);

  const setBoolValues = (newBool1, newBool2) => {
    setHomeTeam(newBool1);
    setCategoryList(newBool2);
  };

  return (
    <TeamContext.Provider value={{ homeTeam, categoryList, setBoolValues }}>
      {children}
    </TeamContext.Provider>
  );
};

export const useBoolValues = () => {
  const context = useContext(TeamContext);
  if (!context) {
    throw new Error('useBoolValues must be used within a TeamProvider');
  }
  return context;
};
