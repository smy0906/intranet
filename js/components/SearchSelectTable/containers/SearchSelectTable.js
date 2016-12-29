import { connect } from 'react-redux';
import { delRow, delSelRows, toggleRow, toggleAllRows, openModal } from '../actions';
import SearchSelectTable from '../components/SearchSelectTable';

const getFilteredRows = (rows, searchFilter) => {
  let datas = rows.map(row => row.data);
  let option = searchFilter.options[searchFilter.selected];

  if (!option || (!searchFilter.param1 && !searchFilter.param2)) {
    return datas;
  }

  datas = datas.filter(data => {
    let value = data[option.dataField];
    if (value == undefined) {
      return false;
    }

    if (option.dataType == 'date') {
      let fromDate = new Date('1970-01-01');
      if (searchFilter.param1) {
        fromDate = new Date(searchFilter.param1);
      }

      let toDate = new Date('2030-12-31');
      if (searchFilter.param2) {
        toDate = new Date(searchFilter.param2);
      }

      let targetDate = new Date(value);
      return fromDate <= targetDate && targetDate <= toDate;

    } else {
      return value.toString().indexOf(searchFilter.param1) !== -1;
    }
  });

  return datas;
};

const mapStateToProps = (state) => {
  let newSelected = [];
  state.rows.forEach((row, i) => {
    if (row.isSelected) {
      newSelected.push(i);
    }
  });

  return {
    datas: getFilteredRows(state.rows, state.filter),
    selected: newSelected
  }
};

const mapDispatchToProps = (dispatch, ownProps) => {
  return {
      dispatch: dispatch,
    //selectProps: {
      onSelect: (e, rowIndex) => dispatch(toggleRow(rowIndex)),
      onSelectAll: (e) => dispatch(toggleAllRows(e.currentTarget.checked)),
      // onSelect: (e, rowIndex) => {
      //   let isChecked = e.currentTarget.checked;
      //
      //   let result = true;
      //   if (ownProps.selectProps && ownProps.selectProps.onSelect) {
      //     const data = ownProps.datas[rowIndex];
      //     result = ownProps.selectProps.onSelect(isChecked, rowIndex, data);
      //   }
      //
      //   if (typeof result === 'undefined' || result !== false) {
      //     dispatch(toggleRow(rowIndex))
      //   }
      //
      // },

      // onSelectAll: (e) => {
      //   let isChecked = e.currentTarget.checked;
      //
      //   let changed = undefined;
      //   if (isChecked) {
      //     changed = [];
      //     for (let i=0; i<ownProps.datas.length; ++i) {
      //       if (ownProps.selectedProps.selected.indexOf(i)===-1){
      //         changed.push(i);
      //       }
      //     }
      //
      //   } else {
      //     changed = ownProps.selectedProps.selected.slice();
      //   }
      //
      //   let result = true;
      //   if (ownProps.selectProps && ownProps.selectProps.onSelectAll) {
      //     const datas = changed.map(index => {
      //       return ownProps.datas[index];
      //     });
      //     result = ownProps.selectProps.onSelectAll(isChecked, changed, datas);
      //   }
      //
      //   if (typeof result === 'undefined' || result !== false) {
      //     changed.forEach((rowIndex) => {
      //       dispatch(toggleRow(rowIndex));
      //     });
      //   }
      // }
    //},
    //updateProps: {
      onAdd: () => {
        let newData = {
          uuid:Math.random(),
          request_date:'request_date',
          register_name:'register_name',
          manager_name:'manager_name',
          manger_accept_datetime:'manager_accept_datetime',
          co_accpeter_name:'co_accpeter_name',
          co_accept_datetime:'co_accept_datetime',
          month:'month',
          team:'team',
          product:'product',
          category:'category',
          desc:'desc',
          company_name:'company_name',
          price:'price',
          pay_date:'pay_date',
          tax:'tax',
          tax_export:'tax_export',
          tax_date:'tax_date',
          is_account_book_registered:'is_account_book_registered',
          bank:'bank',
          bank_account:'bank_account',
          bank_account_owner:'bank_account_owner',
          note:'note',
          paytype:'paytype',
          status:'status'
        };
        dispatch(openModal('add'))
      },

      onEdit: (rowIndex) => dispatch(openModal('edit', rowIndex)),
      onDel: (rowIndex) => dispatch(delRow(rowIndex)),
      onMultiEdit: () => dispatch(openModal('add')),
      onMultiDel: () => dispatch(delSelRows()),
    //}
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(SearchSelectTable);
