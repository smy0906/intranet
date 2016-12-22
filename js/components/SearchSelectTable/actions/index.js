export const makeRows = (from) => ({
  type: 'MAKE_ROWS',
  from
});

export const addRow = (data) => ({
  type: 'ADD_ROW',
  data
});

export const editRow = (rowIndex, data) => ({
  type: 'EDIT_ROW',
  data
});

export const delRow = (rowIndex) => ({
  type: 'DEL_ROW',
  rowIndex
});

export const toggleRow = (rowIndex) => ({
  type: 'TOGGLE_ROW',
  rowIndex
});

export const openModal = (rowIndex, data, editMode) => ({
  type: 'OPEN_MODAL',
  rowIndex,
  editMode,
  data
});

export const closeModal = (isConfirm) => {
  return (dispatch, getState) => {
    dispatch({
      type: 'CLOSE_MODAL'
    });

    if (isConfirm) {
      let state = getState();
      if (state.modal.editMode == 'add') {
        return dispatch(addRow(state.modal.data));

      } else if (state.modal.editMode == 'edit') {
        return dispatch(editRow(state.modal.editingIndex, state.modal.data));
      }

    }
  }
};
