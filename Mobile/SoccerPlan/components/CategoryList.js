import React, { useEffect, useState } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Image } from 'react-native';

import StatsInfo from './StatsInfo';
import sendDataToApi from '../getPostApi/sendDataToApi';
import List from './List';
import { useUser } from './Context/UserContext';
import { useBoolValues } from './Context/TeamContext';

const CategoryList = ({ teamData, onChange }) => {
  const { dataUser } = useUser();
  const { homeTeam, categoryList, setBoolValues } = useBoolValues();
  const [showStatsTeam, setShowStatsTeam] = useState(false);
  const [showStatsPlayers, setShowStatsPlayers] = useState(false);
  const [showChampionship, setShowChampionship] = useState(false);
  const [statsTeam , setStatsTeam] = useState();
  const [statsPlayers , setStatsPlayers] = useState();
  const [championship , setChampionship] = useState();
  const [adversaries , setAdversaries] = useState();

  const handleStatsTeam = () => {
    setShowStatsTeam(true);
    setBoolValues(homeTeam, !categoryList);
    const getStats = async () => {
      const apiResponse = await sendDataToApi('Teams', 'getStatistics', {idTeam:  dataUser.idTeam});
      setStatsTeam(apiResponse.data);
    }
    getStats();
  };

  const handleCancelStatsTeam = () => {
    setShowStatsTeam(!showStatsTeam);
    setBoolValues(homeTeam, !categoryList);
  };

  const handleStatsPlayers = () => {
    setShowStatsPlayers(true);
    setBoolValues(homeTeam, !categoryList);
    const getStats = async () => {
      const apiResponse = await sendDataToApi('Players', 'getStatistics', {idTeam:  dataUser.idTeam});
      console.log('Api Response: ',apiResponse.data);
      setStatsPlayers(apiResponse.data);
    }
    getStats();
  };

  const handleCancelStatsPlayers = () => {
    setShowStatsPlayers(!showStatsPlayers);
    setBoolValues(homeTeam, !categoryList);
  };

  const handleChampionship = () => {
    setShowChampionship(true);
    setBoolValues(homeTeam, !categoryList);

    const getAdversaries = async () => {
      const apiResponse = await sendDataToApi('Adversaries', 'getAdversariesChamp', {idChampionship: championship.idChampionship});
      console.log('Api Response: ',apiResponse.data);
      setAdversaries(apiResponse.data);
    }
    getAdversaries();
  };

  const handleCancelChampionship = () => {
    setShowChampionship(!showChampionship);
    setBoolValues(homeTeam, !categoryList);
  };

  const truncateString = (str, maxLength) => {
    if (str.length > maxLength) {
      return str.substring(0, maxLength) + '...';
    }
    return str;
  };

  useEffect(() => {
    if(dataUser.idTeam != null){
      const getChampionship = async () => {
        const apiResponse = await sendDataToApi('Championships', 'getChampionship', {"idTeam": dataUser.idTeam});
        // console.log(apiResponse.status);
        if(apiResponse.data != null){
          setChampionship(apiResponse.data);
        } else {
          setNumberOfRows(0);
        }
      }
      getChampionship();
    }
  }, [])

  return (
    <>
    {!categoryList &&(
      <View style={styles.container}>
        <TouchableOpacity onPress={() => handleStatsTeam()}>
            <View style={styles.cardCategory}>
              <Image source={require('../assets/img/statsTeam.png') } style={styles.categoryImage} />
              <Text style={styles.categoryTitle}>Stats of {teamData ? truncateString(teamData.nameTeam, 15) : 'Team'}</Text>
            </View>
        </TouchableOpacity>

        <TouchableOpacity onPress={() => handleStatsPlayers()}>
            <View style={styles.cardCategory}>
              <Image source={require('../assets/img/statsPlayer.png') } style={styles.categoryImage} />
              <Text style={styles.categoryTitle}>Stats of Players</Text>
            </View>
        </TouchableOpacity>
      
        <TouchableOpacity onPress={() => handleChampionship()}>
            <View style={styles.cardCategory}>
              <Image source={require('../assets/img/championship.png') } style={styles.categoryImage} />
              <Text style={styles.categoryTitle}>{championship ? championship['nameChampionship'] : 'Championship'}</Text>
            </View>
        </TouchableOpacity>
      </View>
    )}

    {showStatsTeam &&(
      <StatsInfo title={`Stats of ${teamData.nameTeam}`} stats={statsTeam} onCancel={handleCancelStatsTeam}/>
    )}

    {showStatsPlayers && statsPlayers &&(
        <List type={'StatsPlayer'} typeUser={'Coach'} title={'Stats of Players'} members={statsPlayers} onCancel={handleCancelStatsPlayers}/>
    )}

    {showChampionship &&(
      // <StatsInfo title={championship.nameChampionship ? championship.nameChampionship : 'Championship'} stats={adversaries} onCancel={handleCancelChampionship}/>
      <List type={'Championship'} typeUser={'Coach'} value={championship.idChampionship} title={'Adversaries'} members={adversaries} onCancel={handleCancelChampionship}/>
    )}
    </>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'column',
    justifyContent: 'space-between',
    marginTop: '15%',
    // backgroundColor: 'blue',
    width: '90%'
  },
  cardCategory: {
    width: '100%',
    flexDirection: 'row',
    backgroundColor: '#041b2b',
    borderRadius: 10,
    padding: 15,
    marginBottom: 15,
    alignItems: 'center',
    elevation: 2,
  },
  categoryTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 10,
    color: 'white',
    marginLeft: 20
  },
  categoryImage: {
    width: 50,
    height: 50,
    borderRadius: 15,
    // marginBottom: 5,
    backgroundColor: 'white',
  },
  imagePlaceholder: {
    width: 50,
    height: 50,
    borderRadius: 40,
    backgroundColor: 'white',
    marginBottom: 5,
    // Customize placeholder content
  },
  categoryContent: {
    fontSize: 16,
    color: 'white',
    // Customize content style based on your category
  },
});

export default CategoryList;