import {postJson} from './netActions';

export const addRow2 = (data) => {
  return (dispatch) => {
      postJson('/payments/uid/165', data)
      .then((result) => {
        console.log('result=', result);
        dispatch({type: 'ADD_ROW', data});
      })
      .catch((error) => {
        console.log('error in addRows:', error);
      });
  };
};

export const initRows = (datas) => ({
  type: 'INIT_ROWS',
  datas
});

export const addRow = (data) => ({
  type: 'ADD_ROW',
  data
});

export const addRows = (datas) => ({
  type: 'ADD_ROWS',
  datas
});

export const editRow = (rowIndex, data) => ({
  type: 'EDIT_ROW',
  data
});

export const delRow = (rowIndex) => ({
  type: 'DEL_ROW',
  rowIndex
});

export const delSelRows = () => ({
  type: 'DEL_SEL_ROWS',
});

export const toggleRow = (rowIndex) => ({
  type: 'TOGGLE_ROW',
  rowIndex
});

export const toggleAllRows = (select) => ({
  type: 'TOGGLE_ALL_ROWS',
  select
});
