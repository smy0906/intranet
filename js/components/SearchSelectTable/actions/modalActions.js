import {addRow2, editRow} from './rowActions';

export const openModal = (editMode, rowIndex) => {
  return (dispatch, getState) => {
    let state = getState();
    dispatch({
      type: 'OPEN_MODAL',
      editMode,
      data: state.rows[rowIndex].data,
      rowIndex
    });
  };
};

export const closeModal = (isConfirm) => {
  return (dispatch, getState) => {
    dispatch({
      type: 'CLOSE_MODAL'
    });

    if (isConfirm) {
      let state = getState();
      if (state.modal.editMode == 'add') {
        return dispatch(addRow2({
          uuid: Math.floor(Math.random() * (10000 - 1000)) + 1000,
          request_date:'2016-12-31',
          register_name:'test',
          manager_name:'test',
          manger_accept_datetime:'2017-01-01',
          co_accpeter_name:'test2',
          co_accept_datetime:'2018-01-01',
          month:'2016-12',
          team:'team',
          product:'product',
          category:'category',
          desc:'desc',
          company_name:'company_name',
          price: 1000,
          pay_date: '2016-12-31',
          tax:0,
          tax_export:'tax_export',
          tax_date:'2016-12-31',
          is_account_book_registered:'is_account_book_registered',
          bank:'bank',
          bank_account:'bank_account',
          bank_account_owner:'bank_account_owner',
          note:'note',
          paytype:'paytype',
          status:'status'
        }));

      } else if (state.modal.editMode == 'edit') {
        return dispatch(editRow(state.modal.rowIndex, state.modal.data));
      }

    }
  }
};
