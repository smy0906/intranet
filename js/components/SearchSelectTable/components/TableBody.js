import React from 'react';
import TableRow from './TableRow';

const TableBody = ({datas, selected, onSelect, onEdit, onDel}) => {
  const rows = datas.map((data, i) =>
    <TableRow key={i}
              data={data}
              selected={selected? (selected.indexOf(i) !== -1) : false}
              onSelect={e => onSelect(e, i)}
              onEdit={() => onEdit(i)}
              onDel={() => onDel(i)}/>
  );

  return (
    <tbody>
      {rows}
    </tbody>
  );
};

export default TableBody;
