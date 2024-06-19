import { Feather } from '@expo/vector-icons';
import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, Dimensions } from 'react-native';

import SubHeader from './HeaderBar/SubHeader';

const StatsInfo = ({ title, stats, onCancel }) => {

  if(!stats){
    return(
      <View style={styles.container}>
        <Text>Loading ...</Text>
      </View>
    )
  }

  return (
    <>
      <View style={styles.container}>
        <SubHeader onCancel={onCancel} title={title} />
        <View style={styles.containerCard}>
          <View style={styles.column}>
            <Card title="Goals Scored" value={stats.goalsScored} />
            <Card title="Yellow Cards" value={stats.yellowCards} />
          </View>
          <View style={styles.column}>
            <Card title="Goals Conceded" value={stats.goalsConceded} />
            <Card title="Red Cards" value={stats.redCards} />
          </View>
        </View>
      </View>
    </>
  );
};

const Card = ({ title, value }) => {
  return (
    <View style={styles.card}>
      <Text style={styles.cardTitle}>{title}</Text>
      <Text style={styles.cardValue}>{value != null ? value : 0}</Text>
    </View>
  );
};

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  container: {
    alignItems: 'center',
  },
  containerCard: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginTop: 20,
    width: windowWidth
  },
  column: {
    flex: 1,
    marginLeft: '2.5%'
  },
  card: {
    backgroundColor: '#041b2b',
    padding: 10,
    borderRadius: 8,
    marginBottom: 20,
    alignItems: 'center',
    width: '95%'
  },
  cardTitle: {
    fontSize: 16,
    color: 'white',
    fontWeight: 'bold',
    marginBottom: 8,
  },
  cardValue: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#f50443', // You can customize the color as needed
  },
  cancelButton: {
    marginTop: 16,
    backgroundColor: '#f50443',
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
    width: '50%'
  },
  cancelButtonText: {
    color: 'white',
    fontWeight: 'bold'
  },
});

export default StatsInfo;
