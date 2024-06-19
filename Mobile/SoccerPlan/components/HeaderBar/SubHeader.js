import React from 'react';
import { Feather, Fontisto, SimpleLineIcons, MaterialCommunityIcons, MaterialIcons } from '@expo/vector-icons';
import { TouchableOpacity, TextInput, View, Text, StyleSheet, Dimensions } from 'react-native';

// import { Container } from './styles';

export default function SubHeader({onCancel, title}) {
  return (
    <View>
        <TouchableOpacity onPress={onCancel}>
          <View style={styles.header}>
              <Feather name='arrow-left-circle' color='white' size={20}/>
              <Text style={styles.title}>{title}</Text>
          </View>
        </TouchableOpacity>
    </View>
  );
}

const windowWidth = Dimensions.get('window').width;
const windowHeight = Dimensions.get('window').height;

const styles = StyleSheet.create({
  header: {
    backgroundColor: '#041b2b',
    width: windowWidth,
    flexDirection: 'row',
    padding: 10
  },
  title: {
    fontSize: 15,
    color: 'white',
    marginLeft: 10, 
  },
  icon: {
    marginTop: 10
  }
});